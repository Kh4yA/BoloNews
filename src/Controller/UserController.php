<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $articlesPublish = $articleRepository->findBy(['auteur' => $user, 'publish' => 1]);
        $articlesNoPublish = $articleRepository->findBy(['auteur' => $user, 'publish' => 0]);
        return $this->render('user/index.html.twig', [
            'categories' => $categories,
            'articlesPublish' => $articlesPublish,
            'articlesNoPublish' => $articlesNoPublish,
        ]);
    }
    #[Route('/user/bannir/{id}', name: 'user_ban')]
    public function bannir(User $user,UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $users = $userRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN') && $user->isStatut() == true) {
            $user->setStatut(0);
            $em->persist($user);
            $em->flush();
        }else{
            $user->setStatut(1);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('admin/listUser.html.twig',[
            'users'=>$users,
        ]);
    }
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function delete(User $user,UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $users = $userRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN')) {
            $em->remove($user);
            $em->flush();
    }
        return $this->render('admin/listUser.html.twig',[
            'users'=>$users,
        ]);
    }
}
