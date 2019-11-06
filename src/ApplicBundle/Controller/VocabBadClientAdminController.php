<?php

namespace ApplicBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Form\AddCourtPracticesForm;
use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabBadClient;
use ApplicBundle\Form\AddBadClientForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VocabBadClientAdminController extends MyAdminController
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

    public function addClientAction(Request $request)
    {
        $form = $this->createForm(AddBadClientForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var VocabBadClient $practice */
            $badClient = $form->getViewData();
            $badClient->setCreated(new \DateTime());

            $this->getEntityManager()->persist($badClient);
            $this->getEntityManager()->flush($badClient);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/BadClient/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'edit' => false
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

    /**
     * Редактирование клиента из черного списка
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editClientAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(VocabBadClient::class);
        $client = $repo->find($id);

        if ($client === null) {
            return $this->redirectToRoute('admin_court_practices_all');
        }

        $form = $this->createForm(AddBadClientForm::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var VocabBadClient $client */
            $client = $form->getViewData();

            $this->getEntityManager()->persist($client);
            $this->getEntityManager()->flush($client);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/BadClient/add.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
            'edit' => true
        ]);
    }

    /**
     * Удалить клиента из черного списка
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeClientAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(VocabBadClient::class);
        $practice = $repo->find($id);

        if ($practice === null) {
            return $this->redirectToViewAll();
        }

        $this->getEntityManager()->remove($practice);
        $this->getEntityManager()->flush();

        return $this->redirectToViewAll();
    }

    private function redirectToViewAll()
    {
        return $this->redirectToRoute('admin_vocab_bad_clients_all');
    }
}
