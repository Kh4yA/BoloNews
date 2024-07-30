<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HeadlineArticleController extends AbstractController
{
    #[Route('/headline/article', name: 'app_headline_article')]
    public function index(): Response
    {
        return $this->render('headline_article/index.html.twig', [
            'controller_name' => 'HeadlineArticleController',
        ]);
    }
}
