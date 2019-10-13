<?php

namespace ApplicBundle\Controller;

use AppBundle\Helper\ArrayHelper;
use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\LogApplicChanges;
use ApplicBundle\Entity\VocabApplicStatus;
use MobilePushBundle\Sender\PushAllMobilePushSender;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApplicAdminController extends MyAdminController
{
    const CRON_KEY = '1234567';

    /**
     * Экшен: Получение списка всех заявок
     */
    public function viewAllAction()
    {
        $applicRepository = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applics = $applicRepository->findBy([], ['id' => 'desc'], 100);

        $applicsJson = [];

        foreach ($applics as $applic) {
            $applicsJson[$applic->getId()]['result'] = $applic->getResult();
        }

        return $this->render('@Applic/Admin/view.all.html.twig', [
            'applics' => $applics,
            'applicsJson' => $applicsJson
        ]);
    }

    /**
     * Перевод заявки на следующий статус
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function applicNextStatusAction(Request $request)
    {
        $applicId = $request->get('applicId');
        if (!($applicId)) {
            return MyJsonResponse::make(false, 'Не указана заявка!');
        }

        $applicRepo = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applic = $applicRepo->find($applicId);
        if ($applic === null) {
            return MyJsonResponse::make(false, 'Заявка не найдена!');
        }

        /** @var VocabApplicStatus $curStatus */
        $curStatus = $applic->getStatus();
        if ($curStatus->getId() == VocabApplicStatus::MAX_NUMBER) {
            return MyJsonResponse::make(false, 'Заявка уже на максимальном этапе!');
        }

        $newStatusId = $curStatus->getId() + 1;

        $statusRepo = $this->getEntityManager()->getRepository(VocabApplicStatus::class);
        /** @var VocabApplicStatus $newStatus */
        $newStatus = $statusRepo->find($newStatusId);

        $oldStatus = $applic->getStatus();

        $applic->setStatus($newStatus);

        try {
            $this->getEntityManager()->persist($applic);
            $this->getEntityManager()->flush($applic);

            $this->addLogApplic(LogApplicChanges::FIELD_STATUS, $applic, $newStatus->getName(), $oldStatus->getName());
        } catch (\Exception $exception) {
            return MyJsonResponse::make(false, 'Проблемы с базой данных: '. $exception->getMessage());
        }

        return MyJsonResponse::make(
            true,
            null,
            [
                'oldStatus' => $curStatus->getName(),
                'newStatus' => $newStatus->getName(),
                'oldStatusId' => $curStatus->getId(),
                'newStatusId' => $newStatus->getId()
            ]
        );
    }

    public function cronNoProcessApplicsAction($key)
    {
        if ($key == self::CRON_KEY) {
            $this->statRefresh();

            $msg = 'Под номерами: ';

            $applics = $this->getStat()->getNoProcessApplics();

            $curDateTime = new \DateTime();
            $curTime = time();

            if (!(count($applics) > 0)) {
                return new JsonResponse(['isSend' => false]);
            }

            $isSend = false;
            /** @var Applic $applic */
            foreach ($applics as $key => $applic) {
                if ($curTime - $applic->getCreated()->getTimestamp() <= 60 * 60 * 24) {
                    unset($applics[$key]);
                    continue;
                }
            }

            if (count($applics) > 0) {
                foreach ($applics as $applic) {
                    $isSend = true;
                    if (next($applics)) {
                        $msg .= $applic->getId() . ', ';
                    } else {
                        $msg .= $applic->getId();
                    }
                }
            }

            if ($isSend) {
                $pushSender = new PushAllMobilePushSender();
                $isSend = $pushSender->push('Необработанные заявки:', $msg);
            }

            return new JsonResponse(['isSend' => $isSend]);
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Установить/сохранить информацию о результатах заявки
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function applicSetResultAction(Request $request)
    {
        $applicId = $request->get('applicId');
        $comment = $request->get('comment');

        if (!($applicId > 0)) {
            return MyJsonResponse::make(false, 'Не указана заявка!');
        }

        $applicRepo = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applic = $applicRepo->find($applicId);

        if ($applic === null) {
            return MyJsonResponse::make(false, 'Заявки не существует!');
        }

        if ($applic->getStatus()->getId() != Applic::STATUS_PROCESSED) {
            return MyJsonResponse::make(false, 'Заявки еще не обработана!');
        }

        $oldComment = $applic->getResult();

        $applic->setResult($comment);

        try {
            $this->getEntityManager()->persist($applic);
            $this->getEntityManager()->flush($applic);

            $this->addLogApplic(LogApplicChanges::FIELD_RESULT, $applic, $comment, $oldComment);

            return MyJsonResponse::make(true);
        } catch (\Exception $exception) {
            return MyJsonResponse::make(false, 'Проблемы с базой данных: '. $exception->getMessage());
        }
    }
}
