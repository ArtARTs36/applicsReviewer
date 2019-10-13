<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Entity\OfferDocument;
use ApplicBundle\Entity\OfferDocumentDeliveryMethod;
use ApplicBundle\Form\AddDeliveryMethod;
use ApplicBundle\Form\AddOfferDocument;
use Symfony\Component\HttpFoundation\Request;

class OfferDocumentAdminController extends MyAdminController
{
    /**
     * Экшен: Получение списка всех документов
     */
    public function viewAllAction()
    {
        $offerRepository = $this->getEntityManager()->getRepository(OfferDocument::class);
        $offers = $offerRepository->findBy([], ['id' => 'desc'], 100);

        return $this->render('@Applic/Admin/Offers/Document/view.all.html.twig', [
            'offers' => $offers,
        ]);
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(AddOfferDocument::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocument $offer */
            $offer = $form->getViewData();
            $offer->setCreated(new \DateTime());

            $this->getEntityManager()->persist($offer);
            $this->getEntityManager()->flush($offer);

            return $this->redirectToRoute('admin_offer_document_all');
        }

        return $this->render('@Applic/Admin/Offers/Document/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
