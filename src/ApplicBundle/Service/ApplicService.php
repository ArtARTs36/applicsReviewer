<?php

namespace ApplicBundle\Service;

use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabApplicStatus;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

class ApplicService
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Applic $applic
     * @throws \Exception
     */
    public function updateNewApplic(&$applic)
    {
        /** @var EntityRepository $statusRepo */
        $statusRepo = $this->getEntityManager()->getRepository(VocabApplicStatus::class);
        $status = $statusRepo->find(Applic::STATUS_NEW);

        $applic->setIP($_SERVER['REMOTE_ADDR']);
        $applic->setCreated(new \DateTime());
        $applic->setStatus($status);
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
