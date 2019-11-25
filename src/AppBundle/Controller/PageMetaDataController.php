<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PageMetaData;
use AppBundle\Entity\Work;
use AppBundle\Entity\WorkService;
use AppBundle\Form\EditPageMetaDataForm;
use AppBundle\Form\EditWorkService;
use AppBundle\Helper\IconHelper;
use AppBundle\Interfaces\MyAdminController;
use Symfony\Component\HttpFoundation\Request;

class PageMetaDataController extends MyAdminController
{
    public function getViewAllPagesAction()
    {
        $pageRepo = $this->getEntityManager()->getRepository(PageMetaData::class);
        $pages = $pageRepo->findAll();

        return $this->render('@App/Admin/PageMetaData/view.all.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'pages' => $pages
        ]);
    }

    public function getViewPageAction($id)
    {
        $workRepo = $this->getEntityManager()->getRepository(PageMetaData::class);
        /** @var Work $work */
        $work = $workRepo->find($id);

        $services = $work->getServices();

        return $this->render('@App/Admin/Services/Works/view.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'services' => $services,
            'work' => $work
        ]);
    }

    public function editPageAction(Request $request, $id)
    {
        $pageRepo = $this->getEntityManager()->getRepository(PageMetaData::class);
        /** @var WorkService $page */
        $page = $pageRepo->find($id);

        if ($page === null) {
            return null;
        }

        $form = $this->createForm(EditPageMetaDataForm::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newService = $form->getData();

            $this->getEntityManager()->persist($newService);
            $this->getEntityManager()->flush($newService);

            return $this->redirectToRoute('admin_pages_meta_data_edit', ['id' => $newService->getId()]);
        }

        return $this->render('@App/Admin/PageMetaData/edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'page' => $page,
            'form' => $form->createView(),
            'edit' => true
        ]);
    }
}