<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CourtPractice;
use AppBundle\Form\AddCourtPracticesForm;
use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\LogApplicChanges;
use ApplicBundle\Entity\VocabApplicStatus;
use Symfony\Component\HttpFoundation\Request;

class CourtPracticeAdminController extends MyAdminController
{
    /**
     * Отображение списка судебных практик
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllPracticesAction()
    {
        $practicesRepo = $this->getEntityManager()->getRepository(CourtPractice::class);
        $practices = $practicesRepo->findAll();

        return $this->render('admin/CourtPractices/view.all.html.twig', [
            'practices' => $practices
        ]);
    }

    /**
     * Добавление новой судебной практики
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addPracticeAction(Request $request)
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
            'form' => $form->createView(),
            'edit' => true
        ]);
    }

    /**
     * Редактирование судебной практики
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPracticeAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(CourtPractice::class);
        $practice = $repo->find($id);

        if ($practice === null) {
            return $this->redirectToRoute('admin_court_practices_all');
        }

        $form = $this->createForm(AddCourtPracticesForm::class, $practice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CourtPractice $practice */
            $practice = $form->getViewData();
            $practice->setCreated(new \DateTime());

            $this->getEntityManager()->persist($practice);
            $this->getEntityManager()->flush($practice);

            return $this->redirectToRoute('admin_court_practices_all');
        }

        return $this->render('admin/CourtPractices/add.html.twig', [
            'form' => $form->createView(),
            'practice' => $practice,
            'edit' => false
        ]);
    }

    /**
     * Удалить судебную практику
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removePracticeAction(Request $request, $id)
    {
        $repo = $this->getEntityManager()->getRepository(CourtPractice::class);
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
        return $this->redirectToRoute('admin_court_practices_all');
    }
}
