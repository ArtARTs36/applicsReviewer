<?php

namespace AppBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Controller\ApplicAdminController;
use ApplicBundle\Entity\Applic;
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

    public function settingsAction()
    {
        return $this->render('admin/settings.html.twig', [
            'cronKey' => ApplicAdminController::CRON_KEY,
            'pushLinkFeed' => PushAllMobilePushSender::LINK_FEED
        ]);
    }
}
