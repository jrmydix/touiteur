<?php

namespace App\Controller;

use App\Entity\Touite;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\TouiteType;
use App\Form\CommentType;
use App\Repository\TouiteRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class TouiteController extends AbstractController
{
    #[Route('/', name: 'app_touite_index', methods: ['GET'])]
    public function index(TouiteRepository $touiteRepository): Response
    {
        return $this->render('touite/index.html.twig', [
            'touites' => $touiteRepository->findAll(),
        ]);
    }

    #[Route('/compose/touite', name: 'app_touite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TouiteRepository $touiteRepository): Response
    {
        $touite = new Touite();
        $form = $this->createForm(TouiteType::class, $touite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $touite->setAuthor($this->getUser());

            $touiteRepository->add($touite);
            return $this->redirectToRoute('app_touite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('touite/new.html.twig', [
            'touite' => $touite,
            'form' => $form,
        ]);
    }

    #[Route('/status/{id}', name: 'app_touite_show', methods: ['GET', 'POST'])]
    public function show(Touite $touite, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $commentForm = $this-> createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setTouite($touite);
            $comment->setAuthor($this->getUser());
            $commentRepository->add($comment);

            return $this->redirectToRoute('app_touite_show', ['id' => $touite->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('touite/show.html.twig', [
            'touite' => $touite,
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/status/{id}/edit', name: 'app_touite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Touite $touite, TouiteRepository $touiteRepository): Response
    {
        $form = $this->createForm(TouiteType::class, $touite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $touiteRepository->add($touite);
            return $this->redirectToRoute('app_touite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('touite/edit.html.twig', [
            'touite' => $touite,
            'form' => $form,
        ]);
    }

    #[Route('/status/{id}', name: 'app_touite_delete', methods: ['POST'])]
    public function delete(Request $request, Touite $touite, TouiteRepository $touiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$touite->getId(), $request->request->get('_token'))) {
            $touiteRepository->remove($touite);
        }

        return $this->redirectToRoute('app_touite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/like/{id}', name: 'app_touite_like', methods: ['GET'])]
    public function like(Touite $touite, TouiteRepository $touiteRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $likes = $user->getLikes();

        if ($likes->contains($touite)) {
            $touite->removeLike($user);
        } else {
            $touite->addLike($user);
        }

        $touiteRepository->add($touite);

        return $this->redirectToRoute('app_touite_show', ['id' => $touite->getId()], Response::HTTP_SEE_OTHER);
    }
}
