<?php

namespace GuestBookBundle\Controller;

use AppBundle\Interfaces\MyClientPartController;
use GuestBookBundle\Entity\Note;
use GuestBookBundle\Form\AddGuestBookNoteForm;
use MobilePushBundle\Sender\PushAllMobilePushSender;
use Symfony\Component\HttpFoundation\Request;

class GuestBookController extends MyClientPartController
{
    public function getHomeAction(Request $request)
    {
        $noteRepo = $this->getEntityManager()->getRepository(Note::class);
        $notes = $noteRepo->findBy(['active' => true], ['id' => 'desc']);

        $form = $this->createForm(AddGuestBookNoteForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Note $newNote */
            $newNote = $form->getData();
            $newNote->setCreated(new \DateTime());
            $newNote->setActive(false);

            $this->saveEntity($newNote);

            $sender = new PushAllMobilePushSender();
            $sender->push('Отзыв №'. $newNote->getId(), $newNote->getMessage());

            return $this->redirectToRoute('guestbook_success');
        }

        $sumRating = 0;
        /** @var Note $note */
        foreach ($notes as $note) {
            $sumRating += $note->getRating();
        }

        $middleRating = floor($sumRating / count($notes));

        return $this->render('@GuestBook/ClientPart/home.html.twig', [
            'notes' => $notes,
            'form' => $form->createView(),
            'middleRating' => $middleRating
        ]);
    }

    public function getSuccessAction()
    {
        return $this->render('@GuestBook/ClientPart/success.html.twig');
    }
}
