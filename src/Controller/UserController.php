<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\TouiteRepository;
use App\Repository\UserRepository;
use App\Form\UserEditType;

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

    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedPicture = $form['picture']->getData();
            $uploadedBanner = $form['banner']->getData();

            if ($uploadedPicture) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $picture = urlencode(($form['username']->getData()).'-'.uniqid().'.'.$uploadedPicture->guessExtension());
                
                $uploadedPicture->move(
                    $destination,
                    $picture
                );

                $user->setPicture($picture);
            }
            if ($uploadedBanner) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $banner = urlencode(($form['username']->getData()).'-'.uniqid().'.'.$uploadedBanner->guessExtension());
                
                $uploadedBanner->move(
                    $destination,
                    $banner
                );

                $user->setBanner($banner);
            }

            $userRepository->add($user);
            return $this->redirectToRoute('app_user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
