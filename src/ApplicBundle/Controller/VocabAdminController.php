<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabBadClient;
use ApplicBundle\Form\AddBadClientForm;
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
}
