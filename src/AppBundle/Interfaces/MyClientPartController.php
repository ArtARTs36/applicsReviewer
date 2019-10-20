<?php

namespace AppBundle\Interfaces;

use ApplicBundle\Entity\OfferDocument;
use Symfony\Component\HttpFoundation\Response;

class MyClientPartController extends MyController
{
    private function getListOfferDocuments()
    {
        $offerRepo = $this->getEntityManager()->getRepository(OfferDocument::class);

        return $offerRepo->findBy([], ['id' => 'desc'], 4);
    }

    public function render($view, array $parameters = [], Response $response = null)
    {
        $parameters['offerDocuments'] = $this->getListOfferDocuments();

        return parent::render($view, $parameters, $response);
    }
}
