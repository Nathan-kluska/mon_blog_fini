<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use Cocur\Slugify\Slugify;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CreerArticleController extends AbstractController
{
    #[Route('/creer-article', name: 'ajouter_article')]
    public function ajouterArticle(EntityManagerInterface $manager, Request $request, UserInterface $user): Response
    {
        //On crée un article
        $article = new Article();

        // On crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        // on écoute le formulaire avec la class Request -> ne pas oublier de l'importer
        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $slugify = new Slugify();
            $slug =  $slugify->slugify($article->getTitreArticle());
            $article->setSlugArticle($slug);
            $article->setDateCreation(new DateTime());
            $article->setIdUtilisateur($user);
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_dashboard', ['id' => $article->getIdArticle()]);
        }

        return $this->render('creer_article/add.html.twig', [
            'controller_name' => 'CreerArticleController', $user->getNom() . '' . $user->getPrenom(),
            'articleForm' => $articleForm->createView()
        ]);
    }
}

