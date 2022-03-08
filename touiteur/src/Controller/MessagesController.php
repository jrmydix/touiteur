<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageType;
use App\Repository\MessageRepository;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('messages/index.html.twig', [
            'messages' => $messageRepository->findByUser($this->getUser()),
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route('/messages/compose', name: 'app_messages_new')]
    public function send(Request $request, MessageRepository $messageRepository)
    {
        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $messageRepository->add($message);
            return $this->redirectToRoute('app_messages', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('messages/compose.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/messages/{id}', name: 'app_messages_show', methods: ['GET', 'POST'])]
    public function show(Message $message, Request $request, MessageRepository $messageRepository): Response
    {
        $messageNew = new Message();
        $messageForm = $this->createForm(MessageType::class, $messageNew);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $messageNew->setSender($this->getUser());
            $messageRepository->add($messageNew);

            return $this->redirectToRoute('app_messages_show', ['id' => $message->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('messages/show.html.twig', [
            'message' => $message,
            'messageForm' => $messageForm->createView()
        ]);
    }

    #[Route('/messages/{id}', name: 'app_messages_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $messageRepository->remove($message);
        }

        return $this->redirectToRoute('app_messages', [], Response::HTTP_SEE_OTHER);
    }
}
