<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File; // Import File constraint
use Symfony\Component\Validator\Constraints\Image; // Import Image constraint

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de publication'
            ])
            ->add('category', TextType::class, [
                'label' => 'Catégorie (Tips, Training, etc.)',
                'required' => false
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['rows' => 10]
            ])
            // Change imageUrl to FileType
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l\'article (JPG, PNG)',
                'mapped' => false, // Not directly mapped to entity property
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image JPEG ou PNG valide.',
                    ]),
                    new Image([
                        'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 5 Mo.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}