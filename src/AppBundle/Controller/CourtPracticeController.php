<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Interfaces\MyClientPartController;

class CourtPracticeController extends MyClientPartController
{
    public function getAllPracticesAction()
    {
        $repo = $this->getEntityManager()->getRepository(CourtPractice::class);
        $practices = $repo->findBy([]);

        return $this->render('@App/ClientPart/Pages/CourtPractices/home.html.twig', [
            'courtPractices' => $practices
        ]);
    }
}
