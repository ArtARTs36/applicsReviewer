<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
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
        $applics = $applicRepository->findAll();

        return $this->render('@Applic/Admin/view.all.html.twig', [
            'applics' => $applics
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

        $applic->setStatus($newStatus);

        try {
            $this->getEntityManager()->persist($applic);
            $this->getEntityManager()->flush($applic);
        } catch (\Exception $exception) {
            return MyJsonResponse::make(false, 'Проблемы с базой данных');
        }

        return MyJsonResponse::make(
            true,
            null,
            [
                'oldStatus' => $curStatus->getName(),
                'newStatus' => $newStatus->getName()
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
            foreach ($applics as $applic) {
                if ($curTime - $applic->getCreated()->getTimestamp() <= 10 * 60) {
                    continue;
                }
                $isSend = true;
                if (next($applics)) {
                    $msg .= $applic->getId() . ', ';
                }
                {
                    $msg .= $applic->getId();
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
}
