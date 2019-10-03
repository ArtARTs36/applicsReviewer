<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabApplicStatus;

class ApplicAdminController extends MyController
{
    /**
     * Экшен: Получение списка всех заявок
     */
    public function getAllApplicsAction()
    {
        $applicRepository = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applics = $applicRepository->findAll();

        return $this->render('@Applic/Admin/all.item.html.twig', $applics);
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
}
