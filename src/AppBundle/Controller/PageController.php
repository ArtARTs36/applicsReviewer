<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Work;
use AppBundle\Interfaces\MyClientPartController;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\OfferDocument;
use ApplicBundle\Form\AddApplicForm;
use ApplicBundle\Form\AddApplicForOfferDocumentForm;
use ApplicBundle\Service\ApplicService;
use MobilePushBundle\Sender\PushAllMobilePushSender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends MyClientPartController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        return $this->render('ClientPart/Pages/homepage.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    public function getServiceDocumentsAction(Request $request)
    {
        $docsRepo = $this->getEntityManager()->getRepository(OfferDocument::class);
        $docs = $docsRepo->findAll();

        return $this->render('@App/ClientPart/Pages/Services/documents.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'docs' => $docs
        ]);
    }

    public function getWorkPageAction($workUrl)
    {
        $workRepo = $this->getEntityManager()->getRepository(Work::class);
        /** @var Work $work */
        $work = $workRepo->findOneBy(['url' => $workUrl]);

        dump($workUrl);
        dump($work);

        return $this->render('@App/ClientPart/Pages/Services/Works/family.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'work' => $work,
        ]);
    }

    public function getWorksFamilyAction(Request $request)
    {
        $workRepo = $this->getEntityManager()->getRepository(Work::class);
        /** @var Work $work */
        $work = $workRepo->find(1);

        $services = $work->getServices();

        return $this->render('@App/ClientPart/Pages/Services/Works/family.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'services' => $services
        ]);
    }

    public function getServiceDocumentAction(Request $request, $id)
    {
        if (!($id > 0)) {
            return $this->redirectToRoute('homepage');
        }

        $docRepo = $this->getEntityManager()->getRepository(OfferDocument::class);
        /** @var OfferDocument $doc */
        $doc = $docRepo->find($id);

        if ($doc === null) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(AddApplicForOfferDocumentForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $applicService = new ApplicService($this->getEntityManager());

            /** @var Applic $applic */
            $applic = $form->getViewData();
            $applicService->updateNewApplic($applic);

            $applic->setMessage('Заявка на изготовление документа "'. $doc->getName() .'"');

            $this->getEntityManager()->persist($applic);
            $this->getEntityManager()->flush($applic);

            $sender = new PushAllMobilePushSender();
            $sender->push('Заявка №'. $applic->getId(), $applic->getMessage());

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@App/ClientPart/Pages/Services/document.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'doc' => $doc,
            'addApplicForm' => $form->createView()
        ]);
    }

    public function getCallBackAction(Request $request)
    {
        $form = $this->createForm(AddApplicForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $applicService = new ApplicService($this->getEntityManager());

            /** @var Applic $applic */
            $applic = $form->getViewData();
            $applicService->updateNewApplic($applic);

            $this->getEntityManager()->persist($applic);
            $this->getEntityManager()->flush($applic);

            $sender = new PushAllMobilePushSender();
            $sender->push('Заявка №'. $applic->getId(), $applic->getMessage());

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@App/ClientPart/Pages/callback.html.twig', [
            'addApplicForm' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
