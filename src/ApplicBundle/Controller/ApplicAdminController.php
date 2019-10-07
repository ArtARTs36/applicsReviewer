<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabApplicStatus;
use MobilePushBundle\Sender\PushAllMobilePushSender;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

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
     * Экшен: Обновление заявки
     * @param $applicId
     * @param $statusId
     * @return MyJsonResponse|bool
     */
    public function updateApplicStatus($applicId, $statusId)
    {
        if (!($applicId > 0) || !($statusId > 0)) {
            return false;
        }

        $statusRepo = $this->getEntityManager()->getRepository(VocabApplicStatus::class);
        /** @var VocabApplicStatus $status */
        $status = $statusRepo->find($statusId);

        if ($status->getId() != $statusId) {
            return false;
        }

        $applicRepository = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applic = $applicRepository->find($applicId);

        $applic->setStatus($status);

        return new MyJsonResponse(true, 'Статус у заявки #'. $applic->getId() .' изменен!');
    }

    public function cronNoProcessApplicsAction($key)
    {
        if ($key == self::CRON_KEY) {
            $this->statRefresh();

            $msg = 'Под номерами: ';

            $applics = $this->getStat()->getNoProcessApplics();

            $curDateTime = new \DateTime();

            if (!(count($applics) > 0)) {
                return new JsonResponse(['isSend' => false]);
            }

            $isSend = false;
            /** @var Applic $applic */
            foreach ($applics as $applic) {
                if ($applic->getCreated()->diff($curDateTime)->h <= 24) {
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
                $pushSender->push('Необработанные заявки:', $msg);
            }

            return new JsonResponse(['isSend' => $isSend]);
        }

        return $this->redirectToRoute('homepage');
    }
}
