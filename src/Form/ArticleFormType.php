<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreArticle')
            //->add('slugArticle')
            ->add('contenuArticle', CKEditorType::class)
            //->add('dateCreation')
            ->add('idSousCategorie', EntityType::class, [

                'class' => SousCategorie::class,

                'choice_label' => 'nomSousCategorie',

                 'label' => 'Choisissez une catégorie',

                 'attr' => ['class' => 'form-select'],
                 

            ])
            //->add('idUtilisateur',)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
