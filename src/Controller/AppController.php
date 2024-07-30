<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleNumberOneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function index(ArticleRepository $articleRepository, ArticleNumberOneRepository $articleNumberOneRepository): Response
    {
        $articles = $articleRepository->findAll();
        $articleOne = $articleNumberOneRepository->findOneBy([],["date_at"=>"DESC"]);
        return $this->render('app/index.html.twig', [
            'articles' => $articles,
            'articleOne' => $articleOne
        ]);
    }
    #[Route('/articles', name: 'app_articles')]
    public function articles(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $articles = $articleRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('app/articles.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
    #[Route('/articles/detail/{id}', name: 'app_detail', methods: ["GET", "POST"])]
    public function detail(Article $article): Response
    {
        return $this->render('app/detail.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/articles/detail/comment/{id}', name: 'app_detail_comment', methods: ["GET", "POST"])]
    public function addComment(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $comment = new Comment();
        $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        if ($request->get('content') != null) {
            $comment->setContent($request->get('content'));
            $comment->setArticle($article);
            $comment->setAuteur($user);
            $comment->setPublicationDateAt($date);
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirectToRoute('app_detail',['id'=>$article->getId()]);
    }
    #[Route('app/verif', name:'app_verif')]
    public function verifUser():Response
    {
        $user = $this->getUser();
        if($this->isGranted('ROLE_USER') && ($user->isStatut() == true)){
            return $this->redirectToRoute('app_app');
        }else{
            return $this->redirectToRoute('app_logout');

        }
    }

}
