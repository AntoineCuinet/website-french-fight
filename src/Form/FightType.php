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
            ->add('fighterA', TextType::class, ['label' => 'admin.form.fight.fighter_a'])
            ->add('fighterB', TextType::class, ['label' => 'admin.form.fight.fighter_b'])
            
            ->add('fighterAAge', IntegerType::class, [
                'label' => 'admin.form.fight.age_a', 
                'required' => false
            ])
            ->add('fighterAHeight', TextType::class, [
                'label' => 'admin.form.fight.height_a', 
                'required' => false,
                'attr' => ['placeholder' => 'admin.form.fight.placeholders.height']
            ])
            ->add('fighterAWeight', TextType::class, [
                'label' => 'admin.form.fight.weight_a', 
                'required' => false,
                'attr' => ['placeholder' => 'admin.form.fight.placeholders.weight']
            ])

            ->add('fighterBAge', IntegerType::class, [
                'label' => 'admin.form.fight.age_b', 
                'required' => false
            ])
            ->add('fighterBHeight', TextType::class, [
                'label' => 'admin.form.fight.height_b', 
                'required' => false,
                'attr' => ['placeholder' => 'admin.form.fight.placeholders.height']
            ])
            ->add('fighterBWeight', TextType::class, [
                'label' => 'admin.form.fight.weight_b', 
                'required' => false,
                'attr' => ['placeholder' => 'admin.form.fight.placeholders.weight']
            ])

            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'admin.form.fight.date'
            ])
            ->add('type', TextType::class, [
                'label' => 'admin.form.fight.type'
            ])
            ->add('eventName', TextType::class, [
                'label' => 'admin.form.fight.event_name',
                'required' => false
            ])
            ->add('weightClass', TextType::class, [
                'label' => 'admin.form.fight.weight_class',
                'required' => false
            ])
            ->add('rounds', IntegerType::class, [
                'label' => 'admin.form.fight.rounds',
                'required' => false
            ])
            ->add('isTitleFight', CheckboxType::class, [
                'label' => 'admin.form.fight.is_title_fight',
                'required' => false
            ])
            ->add('status', TextType::class, [
                'label' => 'admin.form.fight.status',
                'required' => false
            ])
            ->add('result', TextareaType::class, [
                'required' => false, 
                'label' => 'admin.form.fight.result'
            ])
            ->add('location', TextType::class, [
                'required' => false, 
                'label' => 'admin.form.fight.location'
            ])
            ->add('broadcaster', TextType::class, [
                'required' => false, 
                'label' => 'admin.form.fight.broadcaster'
            ])
            // Change imageUrl to FileType
            ->add('imageFile', FileType::class, [
                'label' => 'admin.form.fight.image_file',
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
            'data_class' => Fight::class,
            'translation_domain' => 'admin',
        ]);
    }
}