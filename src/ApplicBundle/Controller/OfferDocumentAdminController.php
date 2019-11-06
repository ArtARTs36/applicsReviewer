<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Entity\OfferDocument;
use ApplicBundle\Form\AddOfferDocumentForm;
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
        $form = $this->createForm(AddOfferDocumentForm::class, null);

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
            'edit' => false
        ]);
    }

    /**
     * Редактирование оффера
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editOfferAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocument::class);
        $offer = $repo->find($id);

        if ($offer === null) {
            return $this->redirectToRoute('admin_court_practices_all');
        }

        $form = $this->createForm(AddOfferDocumentForm::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocument $offer */
            $offer = $form->getViewData();

            $this->getEntityManager()->persist($offer);
            $this->getEntityManager()->flush($offer);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Offers/Document/add.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
            'offer' => $offer
        ]);
    }

    /**
     * Удалить оффер
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeOfferAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocument::class);
        $practice = $repo->find($id);

        if ($practice === null) {
            return $this->redirectToViewAll();
        }

        $this->getEntityManager()->remove($practice);
        $this->getEntityManager()->flush();

        return $this->redirectToViewAll();
    }

    private function redirectToViewAll()
    {
        return $this->redirectToRoute('admin_offer_document_all');
    }
}
