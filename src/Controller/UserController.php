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
use App\Repository\CommentRepository;

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

    #[Route('/user/{id}/likes', name: 'app_user_liked')]
    public function liked(User $user): Response
    {
        return $this->render('user/likes.html.twig', [
            'user' => $user,
            'touites' => $user->getLikes(),
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}/replies', name: 'app_user_replies')]
    public function replies(User $user, CommentRepository $commentRepository): Response
    {
        return $this->render('user/replies.html.twig', [
            'user' => $user,
            'touites' => $commentRepository->findByAuthor($user),
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}/media', name: 'app_user_media')]
    public function media(User $user, TouiteRepository $touiteRepository): Response
    {
        return $this->render('user/media.html.twig', [
            'user' => $user,
            'touites' => $touiteRepository->findAllByAuthorWithMedia($user),
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

    #[Route('/user/{id}/follow', name: 'app_user_follow', methods: ['GET'])]
    public function follow(User $targetUser, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $followings = $user->getFollowing();
        $followers = $targetUser->getFollower();

        if ($followings->contains($targetUser) && $followers->contains($user)) {
            $user->removeFollowing($targetUser);
            $targetUser->removeFollower($user);
        } else {
            $user->addFollowing($targetUser);
            $targetUser->addFollower($user);
        }

        $userRepository->add($user);
        $userRepository->add($targetUser);

        return $this->redirectToRoute('app_user', ['id' => $targetUser->getId()], Response::HTTP_SEE_OTHER);
    }
}
