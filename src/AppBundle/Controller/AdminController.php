<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Form\AddCourtPracticesForm;
use AppBundle\Form\EditPushAllSettingsForm;
use AppBundle\Interfaces\MyAdminController;
use AppBundle\Service\SiteConfig;
use ApplicBundle\Controller\ApplicAdminController;
use ApplicBundle\Entity\Applic;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends MyAdminController
{
    use StatControlVersionAdminControllerTrait;

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

            return $this->redirectToRoute('admin_court_practices_add');
        }

        return $this->render('admin/CourtPractices/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function settingsAction(Request $request)
    {
        $formPushAllSettings = $this->genFormPushAll($request);
        $configVersions = $this->getAllConfig();

        return $this->render('admin/settings.html.twig', [
            'cronKey' => ApplicAdminController::CRON_KEY,
            'pushLinkFeed' => $this->getConfig()->getValue(SiteConfig::PARAM_PUSHALL_FEED_LINK),
            'configVersions' => $configVersions,
            'formPushAllSettings' => $formPushAllSettings->createView(),
        ]);
    }

    public function genFormPushAll(Request $request)
    {
        $formPushAllSettings = $this->createForm(EditPushAllSettingsForm::class,
            [
                EditPushAllSettingsForm::FIELD_API_KEY => $this->getConfig()->getValue(SiteConfig::PARAM_PUSHALL_API_KEY),

                EditPushAllSettingsForm::FIELD_APPLICATION_ID =>
                    $this->getConfig()->getValue(SiteConfig::PARAM_PUSHALL_APPLICATION_ID),

                EditPushAllSettingsForm::FIELD_FEED_LINK =>
                    $this->getConfig()->getValue(SiteConfig::PARAM_PUSHALL_FEED_LINK)
            ]
        );

        $formPushAllSettings->handleRequest($request);

        if ($formPushAllSettings->isSubmitted() && $formPushAllSettings->isValid()) {
            $data = $formPushAllSettings->getViewData();

            $this->getConfig()->setValue(
                SiteConfig::PARAM_PUSHALL_API_KEY,
                $data[EditPushAllSettingsForm::FIELD_API_KEY]
            );

            $this->getConfig()->setValue(
                SiteConfig::PARAM_PUSHALL_APPLICATION_ID,
                $data[EditPushAllSettingsForm::FIELD_APPLICATION_ID]
            );

            $this->getConfig()->setValue(
                SiteConfig::PARAM_PUSHALL_FEED_LINK,
                $data[EditPushAllSettingsForm::FIELD_FEED_LINK]
            );

            $this->getConfig()->setEntityManager($this->getEntityManager());
            $this->getConfig()->save();

            return $this->redirectToRoute('admin_settings');
        }

        return $formPushAllSettings;
    }
}
