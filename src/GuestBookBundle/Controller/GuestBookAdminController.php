<?php

namespace GuestBookBundle\Controller;

use AppBundle\Helper\ArrayHelper;
use AppBundle\Interfaces\MyAdminController;
use AppBundle\Interfaces\MyJsonResponse;
use GuestBookBundle\Entity\Note;
use GuestBookBundle\Form\EditNoteAdminForm;
use Symfony\Component\HttpFoundation\Request;

class GuestBookAdminController extends MyAdminController
{
    /**
     * Экшен: Получение списка всех отзывов
     */
    public function viewAllAction()
    {
        $noteRepository = $this->getEntityManager()->getRepository(Note::class);
        /** @var Note $notes */
        $notes = $noteRepository->findBy([], ['id' => 'desc']);

        return $this->render('@GuestBook/Admin/view.all.html.twig', [
            'notes' => $notes,
        ]);
    }

    public function editNoteAction(Request $request, $id)
    {
        $noteRepo = $this->getEntityManager()->getRepository(Note::class);
        /** @var Note $note */
        $note = $noteRepo->find($id);

        if ($note === null) {
            return null;
        }

        $form = $this->createForm(EditNoteAdminForm::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newNote = $form->getData();

            $this->getEntityManager()->persist($newNote);
            $this->getEntityManager()->flush($newNote);

            return $this->redirectToRoute('admin_guestbook_admin_note_edit', ['id' => $note->getId()]);
        }

        return $this->render('@GuestBook/Admin/note.edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'note' => $note,
            'form' => $form->createView(),
            'edit' => true
        ]);
    }
}
