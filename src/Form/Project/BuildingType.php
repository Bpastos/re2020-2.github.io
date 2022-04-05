<?php

namespace App\Form\Project;

use App\Entity\Project\Building;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('floorArea', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('livingArea', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('existingFloorArea', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lowFloor', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lowFloorThermal', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Sans planelle ni rupture' => 'Sans planelle ni rupture',
                    'Avec rupture thermique' => 'Avec rupture thermique',
                    'Avec planelle' => 'Avec planelle',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('highFloor', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('highFloorThermal', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Sans planelle ni rupture' => 'Sans planelle ni rupture',
                    'Avec rupture thermique' => 'Avec rupture thermique',
                    'Avec planelle' => 'Avec planelle',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('intermediateFloor', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('intermediateFloorThermal', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Sans planelle ni rupture' => 'Sans planelle ni rupture',
                    'Avec rupture thermique' => 'Avec rupture thermique',
                    'Avec planelle' => 'Avec planelle',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('facades', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('particularWalls', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('plan', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-lg btn-primary mt-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Building::class,
        ]);
    }
}
