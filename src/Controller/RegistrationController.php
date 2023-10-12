<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoleUtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function inscription(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, VilleRepository $repoVille, RoleUtilisateurRepository $repoRoleUtilisateur): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

         








        if ($form->isSubmitted() && $form->isValid()) {
        $nom = $form->get('nom')->getData();
        $prenom  = $form->get('prenom')->getData();
        $adresse = $form->get('adresse')->getData();
        $cp =  $form->get('cp')->getData();
        $nomVille =  $form->get('nomVille')->getData();
        $email =  $form->get('email')->getData();
        $mdp =  $form->get('plainPassword')->getData();
        $codeInsee =  $form->get('citycode')->getData();
        $ville = $repoVille->findOneByCodeInsee($codeInsee);
        $role = $repoRoleUtilisateur->findOneByIdRoleUtilisateur(2);
        
        $ip = $request->getClientIp();
            $hashedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $mdp
            );
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setAdresse($adresse);
            $user->setEmail($email);
            $user->setMdp($hashedPassword);
            $user->setIpInscription($ip);
            $user->setIdVille($ville);
            $user->setCodeRoles('ROLE_USER');
            $user->setIdRoleUtilisateur($role);

            
            


            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login_co');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
