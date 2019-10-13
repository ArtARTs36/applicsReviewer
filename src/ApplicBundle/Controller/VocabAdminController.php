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
    public function viewAllBadClientAction()
    {
        $clientRepository = $this->getEntityManager()->getRepository(VocabBadClient::class);
        /** @var Applic $applic */
        $clients = $clientRepository->findAll();

        return $this->render('@Applic/Admin/Vocab/BadClient/view.all.html.twig', [
            'clients' => $clients
        ]);
    }

    public function addBadClientAction(Request $request)
    {
        $form = $this->createForm(AddBadClientForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var VocabBadClient $practice */
            $badClient = $form->getViewData();
            $badClient->setCreated(new \DateTime());

            $this->getEntityManager()->persist($badClient);
            $this->getEntityManager()->flush($badClient);

            return $this->redirectToRoute('admin_vocab_bad_clients_add');
        }

        return $this->render('@Applic/Admin/Vocab/BadClient/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * Экшн для перевода клиента в черный список
     *
     * @param $applicId
     * @return JsonResponse
     * @throws \Exception
     */
    public function transferClientAction(Request $request)
    {
        $applicId = $request->get('applicId');
        $comment = $request->get('comment');

        if (!($applicId > 0)) {
            return MyJsonResponse::make(false);
        }

        $applicRepo = $this->getEntityManager()->getRepository(Applic::class);
        /** @var Applic $applic */
        $applic = $applicRepo->find($applicId);

        $vocab = new VocabBadClient();
        $vocab->setName($applic->getClientName());
        $vocab->setEmail($applic->getClientMail());
        $vocab->setPhone($applic->getClientPhone());
        $vocab->setCreated(new \DateTime());
        $vocab->setComment($comment);

        try {
            $this->getEntityManager()->persist($vocab);
            $this->getEntityManager()->flush($vocab);

            return MyJsonResponse::make(true);
        } catch (\Exception $exception) {
            return MyJsonResponse::make(false);
        }
    }

    public function viewAllRequiredDocAction()
    {
        $docRepository = $this->getEntityManager()->getRepository(OfferDocumentRequiredDoc::class);
        /** @var OfferDocumentRequiredDoc $docs */
        $docs = $docRepository->findAll();

        return $this->render('@Applic/Admin/Vocab/RequiredDoc/view.all.html.twig', [
            'docs' => $docs
        ]);
    }

    public function addRequiredDocAction(Request $request)
    {
        $form = $this->createForm(AddRequiredDoc::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentRequiredDoc $practice */
            $doc = $form->getViewData();
            $doc->setCreated(new \DateTime());

            $this->getEntityManager()->persist($doc);
            $this->getEntityManager()->flush($doc);

            return $this->redirectToRoute('admin_vocab_required_doc_all');
        }

        return $this->render('@Applic/Admin/Vocab/RequiredDoc/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }



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
