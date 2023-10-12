<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Article;
use Cocur\Slugify\Slugify;
use App\Form\ArticleFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CreerArticleController extends AbstractController
{
    #[Route('/creer-article', name: 'ajouter_article')]
    public function ajouterArticle(EntityManagerInterface $manager, Request $request, UserInterface $user, SluggerInterface $slugger): Response
    {
        //On crée un article
        $article = new Article();

        // On crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article,);

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
            $fichiers = $articleForm->get('nomImage')->getData();
            
            foreach($fichiers as $fichier){
                if ($fichier) {
                    $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    
                    $safeFilename = $slugger->slug($originalFilename);
                    
                    $newFilename = uniqid().'-'.$safeFilename.'-'.uniqid().'.'.$fichier->guessExtension();
                    //dd($newFilename);
                    // Move the file to the directory where brochures are stored
                    try {
                        $fichier->move(
                            $this->getParameter('image_upload'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $image = new Image();
                    $image->setNomImage($newFilename);
                    $image->setIdArticle($article);
                    $manager->persist($image);
                    $manager->flush();
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    //$product->setBrochureFilename($newFilename);
                    
                }
            }
           

            return $this->redirectToRoute('app_dashboard', ['id' => $article->getIdArticle()]);
        }

        return $this->render('creer_article/add.html.twig', [
            'controller_name' => 'CreerArticleController', $user->getNom() . '' . $user->getPrenom(),
            'articleForm' => $articleForm->createView()
        ]);
    }
}

