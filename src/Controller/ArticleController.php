<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use ContainerOIuxqN1\getUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    #[Route('/article/newArticle', name: 'article_new', methods: ["GET", "POST"])]
    public function form(Request $request, EntityManagerInterface $em): Response
    {
        // on intancie un nouvel article
        if ($this->isGranted("ROLE_USER")) {
            $article = new Article();
            $form = $this->createForm(ArticleType::class, $article);
            $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
            $form->handleRequest($request);
            // si le formumaçre est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                // on charge l'obet article
                $article->setCreationDateAt($date);
                $article->setModificationDateAt($date);
                $article->setAuteur($this->getUser());
                $article->setPublish(0);
                // gere l'insertinion de photo 
                // on reccupere le chemin de la photo
                $file = $form->get('picture')->getData();
                $chemin = $this->getParameter('images_directory');
                // on recree le chemon de la photo avec un id unique
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($chemin, $fichier);
                // On charge la photo dans l'objet article
                $article->setPicture($fichier);
                $em->persist($article);
                $em->flush();
                if ($this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('app_admin');
                } else {
                    return $this->redirectToRoute('app_user');
                }
            }
            return $this->render('article/newArticle.html.twig', [
                'form' => $form
            ]);
        } else {
            return $this->redirectToRoute("app_app");
        }
    }
    #[Route('/article/editer/{id}', name: 'article_editer', methods: ["GET", "POST"])]
    public function editer(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isGranted("ROLE_USER") && ($this->getUser() == $article->getAuteur())) {
            $form = $this->createForm(ArticleType::class, $article);
            $date = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
            $form->handleRequest($request);
            $currentPicture = $article->getPicture();
            if ($form->isSubmitted() && $form->isValid()) {
                // Mise à jour de la date de modification
                $article->setModificationDateAt($date);
                // Gestion de la photo
                // si $file n'est pas vide on ajoute une photo et on supprime en bdd la photo est dans le fichier image produit si elle existe
                if (!empty($form->get('picture')->getData())) {
                    $file = $form->get('picture')->getData();
                    // Chemin du répertoire des images
                    $chemin = $this->getParameter('images_directory');
                    // Suppression de l'ancienne photo
                    $oldFilePath = $chemin . '/' . $currentPicture;
                    if (file_exists($oldFilePath) && isset($currentPicture)) {
                        unlink($oldFilePath);
                    }
                    $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($chemin, $fichier);
                    $article->setPicture($fichier);
                } elseif (isset($currentPicture)) {
                }
                $em->persist($article);
                $em->flush();
                if ($this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('app_admin');
                } else {
                    return $this->redirectToRoute('app_user');
                }
            }
            return $this->render('article/editerArticle.html.twig', [
                'form' => $form->createView(),
                'article' => $article
            ]);
        } else {
            return $this->redirectToRoute('app_app');
        }
    }
    #[route('article/search', name: 'article_search', methods: ["GET", "POST"])]
    public function search(CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findBySearch($_GET);
        $categories = $categoryRepository->findAll();
        return $this->render('app/articles.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
    #[route('article/publish/{id}', name: 'article_publish', methods: ["GET"])]
    public function publish(Article $article, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            if ($article->isPublish()) {
                $article->setPublish(0);
                $em->persist($article);
                $em->flush();
            } else {
                $article->setPublish(1);
                $em->persist($article);
                $em->flush();
            }
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin');
            } else {
                return $this->redirectToRoute('app_user');
            }
        }
    }
    #[route('article/delete/{id}', name: 'article_delete', methods: ["GET"])]
    public function delete(Article $article, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            foreach ($article->getComments() as $comment) {
                $em->remove($comment);
            }
            $em->remove($article);
            $em->flush();
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin');
            } else {
                return $this->redirectToRoute('app_user');
            }
        }
    }
    #[route('article/like/{id}', name: 'article_like', methods: ["POST","GET"])]
    public function addLike(Article $article, EntityManagerInterface $em )
    {
        $user = $this->getUser();
        $likes = $article->getNbLike();
        $tab = array();
        foreach ($likes as $like) {
            $tab[] = $like;
        }
        $articles = $em->getRepository(Article::class)->find($article);
        if (!$articles) {
            return new JsonResponse(['error' => 'Article pas trouvé'], 404);
        } else {
            if (in_array($user, $tab)) {
                $article->removeNbLike($user);
                $em->persist($article);
                $em->flush();
                $tab = array();
                foreach ($likes as $like) {
                    $tab[] .= $like;
                }
                return $this->json([
                    'echec' => 'L\'utilisateur a deja liké',
                    'like' => $tab,
                    'liked'=> false,
                ]);
            } else {
                $article->addNbLike($user);
                $em->persist($article);
                $em->flush();
                $tab = array();
                foreach ($likes as $like) {
                    $tab[] .= $like;
                }
                return $this->json([
                    'succes' => 'articles trouvé et liké',
                    'like' => $tab,
                    'liked'=>true,
                ]);
            }
        }
    }
}
