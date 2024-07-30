<?php

namespace App\Form;

use App\Controller\StringToFileTransformer;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Controller\StringToFileTransformerController;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hat', TextareaType::class, [
            'label' => 'Votre chapeau :',
            'constraints' => [
                new Assert\NotBlank([
                    "message" => "Vous devez remplir ce champs"
                ])
            ]
        ])
            ->add('title', TextType::class, [
                'label' => 'Votre titre :',
                'constraints' => [
                    new Assert\NotBlank([
                        "message" => "Vous devez remplir ce champs"
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Veuillez choisir une catégorie :',
                'data_class' => null,
                'class' => Category::class,
                'constraints' => [
                    new Assert\NotBlank([
                        "message" => "Vous devez choisir une catégorie"
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Entrez le contenu de votre articles :',
                'constraints' => [
                    new Assert\NotBlank([
                        "message" => "Vous devez remplir ce champs"
                    ])
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo de votre article :',
                'data_class' => null,
                'mapped'=>false,
            ])
            ->add("Ajouter", SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Article::class
        ]);
    }
}
