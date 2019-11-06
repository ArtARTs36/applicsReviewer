<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Work;
use AppBundle\Entity\WorkService;
use AppBundle\Form\EditWorkService;
use AppBundle\Helper\IconHelper;
use AppBundle\Interfaces\MyAdminController;
use Symfony\Component\HttpFoundation\Request;

class WorkAdminController extends MyAdminController
{
    public function getViewAllWorksAction()
    {
        $workRepo = $this->getEntityManager()->getRepository(Work::class);
        $works = $workRepo->findAll();

        return $this->render('@App/Admin/Services/Works/view.all.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'works' => $works
        ]);
    }

    public function getViewWorkAction($id)
    {
        $workRepo = $this->getEntityManager()->getRepository(Work::class);
        /** @var Work $work */
        $work = $workRepo->find($id);

        $services = $work->getServices();

        return $this->render('@App/Admin/Services/Works/view.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'services' => $services,
            'work' => $work
        ]);
    }

    public function editServiceAction(Request $request, $id)
    {
        $serviceRepo = $this->getEntityManager()->getRepository(WorkService::class);
        /** @var WorkService $service */
        $service = $serviceRepo->find($id);

        if ($service === null) {
            return null;
        }

        $form = $this->createForm(EditWorkService::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newService = $form->getData();

            $this->getEntityManager()->persist($newService);
            $this->getEntityManager()->flush($newService);

            return $this->redirectToRoute('admin_works_view', ['id' => $service->getWork()->getId()]);
        }

        $iconHelper = new IconHelper();

        return $this->render('@App/Admin/Services/Works/service.edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'service' => $service,
            'form' => $form->createView(),
            'edit' => true,
            'iconClasses' => $iconHelper->getClasses(),
            'iconClassesCount' => count($iconHelper->getClasses())
        ]);
    }

    public function addServiceAction(Request $request)
    {
        $newService = new WorkService();
        $form = $this->createForm(EditWorkService::class, $newService, ['isNewEntity' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newService = $form->getData();

            $this->getEntityManager()->persist($newService);
            $this->getEntityManager()->flush($newService);

            return $this->redirectToRoute('admin_works_view', ['id' => $newService->getWork()->getId()]);
        }

        return $this->render('@App/Admin/Services/Works/service.edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'service' => $newService,
            'form' => $form->createView(),
            'edit' => false,
            'iconClasses' => IconHelper::$iconClasses,
            'iconClassesCount' => count(IconHelper::$iconClasses)
        ]);
    }
}
