<?php

namespace App\Form;

use App\Entity\Fight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File; // Import File constraint
use Symfony\Component\Validator\Constraints\Image; // Import Image constraint

class FightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fighterA', TextType::class, ['label' => 'Combattant A'])
            ->add('fighterB', TextType::class, ['label' => 'Combattant B'])
            
            ->add('fighterAAge', IntegerType::class, [
                'label' => 'Age (A)', 
                'required' => false
            ])
            ->add('fighterAHeight', TextType::class, [
                'label' => 'Taille (A)', 
                'required' => false,
                'attr' => ['placeholder' => '1.80m']
            ])
            ->add('fighterAWeight', TextType::class, [
                'label' => 'Poids (A)', 
                'required' => false,
                'attr' => ['placeholder' => '70kg']
            ])

            ->add('fighterBAge', IntegerType::class, [
                'label' => 'Age (B)', 
                'required' => false
            ])
            ->add('fighterBHeight', TextType::class, [
                'label' => 'Taille (B)', 
                'required' => false,
                'attr' => ['placeholder' => '1.80m']
            ])
            ->add('fighterBWeight', TextType::class, [
                'label' => 'Poids (B)', 
                'required' => false,
                'attr' => ['placeholder' => '70kg']
            ])

            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du combat'
            ])
            ->add('type', TextType::class, [
                'label' => 'Type (MMA, Boxe...)'
            ])
            ->add('eventName', TextType::class, [
                'label' => 'Nom de l\'événement',
                'required' => false
            ])
            ->add('weightClass', TextType::class, [
                'label' => 'Catégorie de poids',
                'required' => false
            ])
            ->add('rounds', IntegerType::class, [
                'label' => 'Nombre de rounds',
                'required' => false
            ])
            ->add('isTitleFight', CheckboxType::class, [
                'label' => 'Combat pour le titre ?',
                'required' => false
            ])
            ->add('status', TextType::class, [
                'label' => 'Statut (scheduled, etc.)',
                'required' => false
            ])
            ->add('result', TextareaType::class, [
                'required' => false, 
                'label' => 'Résultat'
            ])
            ->add('location', TextType::class, [
                'required' => false, 
                'label' => 'Lieu'
            ])
            ->add('broadcaster', TextType::class, [
                'required' => false, 
                'label' => 'Diffuseur'
            ])
            // Change imageUrl to FileType
            ->add('imageFile', FileType::class, [
                'label' => 'Image du combat (JPG, PNG)',
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
            'data_class' => Fight::class,
        ]);
    }
}