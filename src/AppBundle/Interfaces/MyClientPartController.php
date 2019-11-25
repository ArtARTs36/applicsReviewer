<?php

namespace AppBundle\Interfaces;

use AppBundle\Entity\PageMetaData;
use AppBundle\Entity\Work;
use AppBundle\Service\SEO\SEOVarsForArrayTwig;
use ApplicBundle\Entity\OfferDocument;
use Symfony\Component\HttpFoundation\Response;

class MyClientPartController extends MyController
{
    const OBJ_GET_SEO_VALUES = 'objForGetValuesSeo';
    const OBJ_GET_SEO_STATIC_ID = 'objForGetValuesSeoStaticId';

    private function getListOfferDocuments()
    {
        $offerRepo = $this->getEntityManager()->getRepository(OfferDocument::class);

        return $offerRepo->findBy([], ['id' => 'desc'], 4);
    }

    private function getAllWorks()
    {
        $repo = $this->getEntityManager()->getRepository(Work::class);

        return $repo->findBy([], ['id' => 'desc'], 4);
    }

    public function render($view, array $parameters = [], Response $response = null)
    {
        $parameters['offerDocuments'] = $this->getListOfferDocuments();
        $parameters['offerDocuments'] = $this->getListOfferDocuments();
        $parameters['worksBlock'] = $this->getAllWorks();

        if (isset($parameters[self::OBJ_GET_SEO_STATIC_ID])) {
            $metaRepo = $this->getEntityManager()->getRepository(PageMetaData::class);
            $meta = $metaRepo->find($parameters[self::OBJ_GET_SEO_STATIC_ID]);

            $parameters['currentPageSeoStatic'] = $meta;

            SEOVarsForArrayTwig::append($parameters, 'currentPageSeoStatic');
        }

        if (isset($parameters[self::OBJ_GET_SEO_VALUES])) {
            SEOVarsForArrayTwig::append($parameters, $parameters[self::OBJ_GET_SEO_VALUES]);
        }

        return parent::render($view, $parameters, $response);
    }

}
