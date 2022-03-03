<?php

namespace App\Controller;

use App\Entity\Touite;
use App\Form\TouiteType;
use App\Repository\TouiteRepository;
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
            $touiteRepository->add($touite);
            return $this->redirectToRoute('app_touite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('touite/new.html.twig', [
            'touite' => $touite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_touite_show', methods: ['GET'])]
    public function show(Touite $touite): Response
    {
        return $this->render('touite/show.html.twig', [
            'touite' => $touite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_touite_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_touite_delete', methods: ['POST'])]
    public function delete(Request $request, Touite $touite, TouiteRepository $touiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$touite->getId(), $request->request->get('_token'))) {
            $touiteRepository->remove($touite);
        }

        return $this->redirectToRoute('app_touite_index', [], Response::HTTP_SEE_OTHER);
    }
}
