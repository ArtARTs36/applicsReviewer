<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Entity\OfferDocumentRequiredDoc;
use ApplicBundle\Form\AddRequiredDoc;
use Symfony\Component\HttpFoundation\Request;

class RequiredDocAdminController extends MyAdminController
{
    /**
     * Посмотреть все документы
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllDocsAction()
    {
        $docRepository = $this->getEntityManager()->getRepository(OfferDocumentRequiredDoc::class);
        /** @var OfferDocumentRequiredDoc $docs */
        $docs = $docRepository->findAll();

        return $this->render('@Applic/Admin/Vocab/RequiredDoc/view.all.html.twig', [
            'docs' => $docs
        ]);
    }

    /**
     * Создать новый документ
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addDocAction(Request $request)
    {
        $form = $this->createForm(AddRequiredDoc::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentRequiredDoc $practice */
            $doc = $form->getViewData();
            $doc->setCreated(new \DateTime());

            $this->getEntityManager()->persist($doc);
            $this->getEntityManager()->flush($doc);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/RequiredDoc/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'edit' => false
        ]);
    }

    /**
     * Редактирование документа
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editDocAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocumentRequiredDoc::class);
        $doc = $repo->find($id);

        if ($doc === null) {
            return $this->redirectToViewAll();
        }

        $form = $this->createForm(AddRequiredDoc::class, $doc);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentRequiredDoc $doc */
            $doc = $form->getViewData();

            $this->getEntityManager()->persist($doc);
            $this->getEntityManager()->flush($doc);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/RequiredDoc/add.html.twig', [
            'form' => $form->createView(),
            'practice' => $doc,
            'edit' => false
        ]);
    }

    /**
     * Удалить документ
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeDocAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocumentRequiredDoc::class);
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
        return $this->redirectToRoute('admin_vocab_required_doc_all');
    }
}
