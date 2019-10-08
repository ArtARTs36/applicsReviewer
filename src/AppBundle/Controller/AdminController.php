<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Form\AddCourtPracticesForm;
use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Controller\ApplicAdminController;
use ApplicBundle\Entity\Applic;
use Symfony\Component\HttpFoundation\Request;
use MobilePushBundle\Sender\PushAllMobilePushSender;

class AdminController extends MyAdminController
{
    public function indexAction()
    {
        $applicRepo = $this->getEntityManager()->getRepository(Applic::class);
        $applics = $applicRepo->findAll();
        $applicsCount = count($applics);

        return $this->render('admin/index.html.twig', [
            'applicsCount' => $applicsCount
        ]);
    }

    /**
     * Функция для отображения списка судебных практик
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function courtPracticeViewAllAction()
    {
        $practicesRepo = $this->getEntityManager()->getRepository(CourtPractice::class);
        $practices = $practicesRepo->findAll();

        return $this->render('admin/CourtPractices/view.all.html.twig', [
            'practices' => $practices
        ]);
    }

    public function courtPracticeAddAction(Request $request)
    {
        $form = $this->createForm(AddCourtPracticesForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CourtPractice $practice */
            $practice = $form->getViewData();
            $practice->setCreated(new \DateTime());

            $this->getEntityManager()->persist($practice);
            $this->getEntityManager()->flush($practice);
        }

        return $this->render('admin/CourtPractices/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function settingsAction()
    {
        return $this->render('admin/settings.html.twig', [
            'cronKey' => ApplicAdminController::CRON_KEY,
            'pushLinkFeed' => PushAllMobilePushSender::LINK_FEED
        ]);
    }
}
