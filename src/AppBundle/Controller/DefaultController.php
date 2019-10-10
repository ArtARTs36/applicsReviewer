<?php

namespace AppBundle\Controller;

use AppBundle\Interfaces\MyController;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Form\AddApplicForm;
use ApplicBundle\Service\ApplicService;
use MobilePushBundle\Sender\PushAllMobilePushSender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends MyController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
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

        return $this->render('default/index.html.twig', [
            'addApplicForm' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
