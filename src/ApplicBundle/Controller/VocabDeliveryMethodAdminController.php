<?php

namespace ApplicBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use ApplicBundle\Entity\OfferDocumentDeliveryMethod;
use ApplicBundle\Form\AddDeliveryMethodForm;
use Symfony\Component\HttpFoundation\Request;

class VocabDeliveryMethodAdminController extends MyAdminController
{
    public function viewAllMethodAction()
    {
        $methodRepository = $this->getEntityManager()->getRepository(OfferDocumentDeliveryMethod::class);
        /** @var OfferDocumentDeliveryMethod $methods */
        $methods = $methodRepository->findAll();

        return $this->render('@Applic/Admin/Vocab/DeliveryMethod/view.all.html.twig', [
            'methods' => $methods
        ]);
    }

    public function addMethodAction(Request $request)
    {
        $form = $this->createForm(AddDeliveryMethodForm::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentDeliveryMethod $method */
            $method = $form->getViewData();
            $method->setCreated(new \DateTime());

            $this->getEntityManager()->persist($method);
            $this->getEntityManager()->flush($method);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/DeliveryMethod/add.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'edit' => false
        ]);
    }

    /**
     * Редактирование способа доставки
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editMethodAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocumentDeliveryMethod::class);
        $method = $repo->find($id);

        if ($method === null) {
            return $this->redirectToViewAll();
        }

        $form = $this->createForm(AddDeliveryMethodForm::class, $method);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferDocumentDeliveryMethod $method */
            $method = $form->getViewData();

            $this->getEntityManager()->persist($method);
            $this->getEntityManager()->flush($method);

            return $this->redirectToViewAll();
        }

        return $this->render('@Applic/Admin/Vocab/DeliveryMethod/add.html.twig', [
            'form' => $form->createView(),
            'method' => $method,
            'edit' => true
        ]);
    }

    /**
     * Удалить способ доставки
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeMethodAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(OfferDocumentDeliveryMethod::class);
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
        return $this->redirectToRoute('admin_vocab_delivery_method_all');
    }
}
