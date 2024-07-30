<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
        if ($this->IsGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $categories = $categoryRepository->findAll();
            $articlesPublish = $articleRepository->findBy(['auteur' => $user, 'publish' => 1]);
            $articlesNoPublish = $articleRepository->findBy(['auteur' => $user, 'publish' => 0]);
            return $this->render('admin/index.html.twig', [
                'categories' => $categories,
                'articlesPublish' => $articlesPublish,
                'articlesNoPublish' => $articlesNoPublish,
            ]);
        }
    }
    #[Route('/admin/AllArticle', name: 'admin_allArticle')]
    public function showAllAtricle(CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
        if ($this->IsGranted('ROLE_ADMIN')) {
            $categories = $categoryRepository->findAll();
            $articlesPublish = $articleRepository->findBy(['publish' => 1]);
            $articlesNoPublish = $articleRepository->findBy(['publish' => 0]);
            return $this->render('admin/allArticle.html.twig', [
                'categories' => $categories,
                'articlesPublish' => $articlesPublish,
                'articlesNoPublish' => $articlesNoPublish,
            ]);
        }
    }
    #[Route('/admin/AllUser', name: 'admin_allUser')]
    public function listAllUser(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/listUser.html.twig',[
            'users'=>$users,
        ]);
    }

}
