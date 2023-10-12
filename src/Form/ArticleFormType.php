<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\Region;
use App\Entity\Categorie;
use App\Entity\Departement;
use App\Entity\SousCategorie;
use App\Form\ArticleFormType;
use Symfony\Component\Form\Forms;
use App\Repository\VilleRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ArticleFormType extends AbstractType
{

    private $sousCategorieRepository;
    
    public function __construct(SousCategorieRepository $sousCategorieRepository)
    {       
        $this->sousCategorieRepository = $sousCategorieRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
            'label' => false,
            'required' => false,
            'attr' => ['class' => 'form-control mt-5']
            ])
            ->add('contenu', TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control mt-2']
            ])
            ->add('prix', TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control mt-2']
                ])
            ->add('adresse', TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control mt-2']
                ])
            
            ->add('idRegion', EntityType::class, [
                'class' => Region::class,
                'mapped' => false,
                'choice_label' => "nomRegion",
                'placeholder' => 'Choisissez la région',                
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-5']
                ])

                ->add('idVille', EntityType::class, [
                'class' => Ville::class,
                'mapped' => false,
                'data' => null,
                'choice_label' => "nomVille",
                'placeholder' => 'Choisissez la ville',                
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-2']
                ])

            ->add('save', SubmitType::class,[
            'attr'=> ['class'=> 'btn btn-success mt-5'],
            'label' => 'Valider '
            ])
            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'mapped' => false,
                'choice_label' => "nomCategorie",
                'placeholder' => 'Choisissez la catégorie',                
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-2']
            ]);
            
            $formModifier = function(FormInterface $form, Categorie $categorie = null){ 
            $sousCategories = null === $categorie ? [] : $this->sousCategorieRepository->findByIdCategorie($categorie);
            
            $form->add('idSousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'placeholder' => 'Choisissez la sous-catégorie',
                'choice_label' => 'nomSousCategorie', 
                'choices' => $sousCategories,
                'multiple' => false,
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-2'
                ]]);
            };
      
            //->add('dateAnnonce')
            //->add('latitude')
            //->add('longitude')
            $builder->get('idCategorie')->addEventListener( 
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) use ($formModifier){
                    $categorie = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $categorie);
                }
            );
            $builder->addEventListener( 
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getIdSousCategorie());
                }
            );
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'allow_extra_fields' => true
        ]);
    }
}
