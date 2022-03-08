<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\TouiteRepository;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(User $user, TouiteRepository $touiteRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'touites' => $touiteRepository->findByAuthor($user),
            'controller_name' => 'UserController',
        ]);
    }
}
