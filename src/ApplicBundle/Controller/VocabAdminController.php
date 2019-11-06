<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\OfferDocumentDeliveryMethod;
use ApplicBundle\Entity\OfferDocumentRequiredDoc;
use ApplicBundle\Entity\VocabBadClient;
use ApplicBundle\Form\AddBadClientForm;
use ApplicBundle\Form\AddDeliveryMethod;
use ApplicBundle\Form\AddRequiredDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VocabAdminController extends MyAdminController
{
    public function viewAllDeliveryMethodAction()
    {
        $methodRepository = $this->getEntityManager()->getRepository(OfferDocumentDeliveryMethod::class);
        /** @var OfferDocumentDeliveryMethod $methods */
        $methods = $methodRepository->findAll();

        return $this->render('@Applic/Admin/Vocab/DeliveryMethod/view.all.html.twig', [
            'methods' => $methods
        ]);
    }

    public function addDeliveryMethodAction(Request $request)
    {
        $form = $this->createForm(AddDeliveryMethod::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentDeliveryMethod $method */
            $method = $form->getViewData();
            $method->setCreated(new \DateTime());

            $this->getEntityManager()->persist($method);
            $this->getEntityManager()->flush($method);

            return $this->redirectToRoute('admin_vocab_delivery_method_all');
        }

        return $this->render('@Applic/Admin/Vocab/DeliveryMethod/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
