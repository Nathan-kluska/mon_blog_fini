<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ArticleRepository $repoArticle, UserInterface $user): Response
    {
        $titre = "Mes articles";
        $idUser = $user->getIdUtilisateur();
        $articlesUser = $repoArticle->findByIdUtilisateur(
            $idUser
        );
        
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'mon_titre' => $titre,
            'articlesUser'=> $articlesUser
            
        ]);
    }
}
