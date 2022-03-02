<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExploreController extends AbstractController
{
    #[Route('/explore', name: 'app_explore')]
    public function index(): Response
    {
        return $this->render('explore/index.html.twig', [
            'controller_name' => 'ExploreController',
        ]);
    }
}
