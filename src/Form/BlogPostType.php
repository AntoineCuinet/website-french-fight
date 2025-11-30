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
            ->add('title', TextType::class, ['label' => 'admin.form.blog.title'])
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'admin.form.blog.published_at'
            ])
            ->add('category', TextType::class, [
                'label' => 'admin.form.blog.category',
                'required' => false
            ])
            ->add('content', TextareaType::class, [
                'label' => 'admin.form.blog.content',
                'attr' => ['rows' => 10]
            ])
            // Change imageUrl to FileType
            ->add('imageFile', FileType::class, [
                'label' => 'admin.form.blog.image_file',
                'mapped' => false, // Not directly mapped to entity property
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'admin.form.constraint.image_mime_type',
                    ]),
                    new Image([
                        'maxSizeMessage' => 'admin.form.constraint.image_max_size',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
            'translation_domain' => 'admin',
        ]);
    }
}