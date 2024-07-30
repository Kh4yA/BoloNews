<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoyController extends AbstractController
{
    #[Route('/categoy', name: 'category_add')]
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Category();
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('category/index.html.twig', [
            'form' => $form,
        ]);
    }
}
