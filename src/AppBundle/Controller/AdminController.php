<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Form\AddCourtPracticesForm;
use AppBundle\Form\EditDesignForm;
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

    public function settingsAction(Request $request)
    {
        $formPushAllSettings = $this->genFormPushAll($request);
        $configVersions = $this->getAllConfig();

        if ($formPushAllSettings->isSubmitted()) {
            return $this->redirectToRoute('admin_settings');
        }

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
        }

        return $formPushAllSettings;
    }

    public function settingsDesignAction(Request $request)
    {
        $formPushAllSettings = $this->createForm(EditDesignForm::class,
            [
                EditDesignForm::FIELD_FOOTER_ADDRESS => $this->getConfig()->getValue(SiteConfig::PARAM_FOOTER_ADDRESS),
                EditDesignForm::FIELD_FOOTER_EMAIL => $this->getConfig()->getValue(SiteConfig::PARAM_FOOTER_EMAIL),
                EditDesignForm::FIELD_FOOTER_CONTACT_PHONE_1 => $this->getConfig()->getValue(SiteConfig::PARAM_FOOTER_PHONE_1),
                EditDesignForm::FIELD_FOOTER_CONTACT_PHONE_2 => $this->getConfig()->getValue(SiteConfig::PARAM_FOOTER_PHONE_2)
            ]
        );

        $formPushAllSettings->handleRequest($request);

        if ($formPushAllSettings->isSubmitted() && $formPushAllSettings->isValid()) {
            $data = $formPushAllSettings->getViewData();

            $this->getConfig()->setValue(
                SiteConfig::PARAM_FOOTER_PHONE_1,
                $data[EditDesignForm::FIELD_FOOTER_CONTACT_PHONE_1]
            );

            $this->getConfig()->setValue(
                SiteConfig::PARAM_FOOTER_PHONE_2,
                $data[EditDesignForm::FIELD_FOOTER_CONTACT_PHONE_2]
            );

            $this->getConfig()->setValue(
                SiteConfig::PARAM_FOOTER_EMAIL,
                $data[EditDesignForm::FIELD_FOOTER_EMAIL]
            );

            $this->getConfig()->setValue(
                SiteConfig::PARAM_FOOTER_ADDRESS,
                $data[EditDesignForm::FIELD_FOOTER_ADDRESS]
            );

            $this->getConfig()->setEntityManager($this->getEntityManager());
            $this->getConfig()->save();

            $this->redirectToRoute('admin_settings_design');
        }

        return $this->render('admin/settings.design.html.twig', [
            'form' => $formPushAllSettings->createView()
        ]);
    }
}
