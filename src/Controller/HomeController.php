<?php

namespace App\Controller;

use src\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $repoArticle): Response
    {
        $titre = "Bienvenue sur l'accueil";
 
        $articlesUser = $repoArticle->findAll(
       
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mon_titre' => $titre,
            'articles' => $articlesUser
        ]);
    }
}
