<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login_co')]
    public function index(): Response
    {   
        $titre = "Espace de connexion";
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'mon_titre' => $titre
        ]);
    }

    #[Route('/deconnexion', name: 'app_login_deco')]
    public function deconnexion(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
