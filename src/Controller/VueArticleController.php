<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VueArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_vue_article')]
    public function index(ArticleRepository $repoArticle, UserInterface $user, $id): Response
    {  
        $article = $repoArticle->findOneByIdArticle($id);
        

        return $this->render('vue_article/index.html.twig', [
            'controller_name' => 'VueArticleController',
            'article' => $article, // Passez l'article à votre modèle Twig si nécessaire
            
        ]);
    }
}
