<?php

namespace App\Form\Project;

use App\Entity\Project\SecondaryHeading;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecondaryHeadingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'En volume chauffé' => 'En volume chauffé',
                    'Hors volume chauffé = cave, garage, etc...' => 'Hors volume chauffé = cave, garage, etc...',
                ],
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('heatingAppliance', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Plancher chauffant' => 'Plancher chauffant',
                    'Radiateur' => 'Radiateur',
                    'Poêle à bois + sèche serviette puissance 500W dans la salle de bain' => 'Poêle à bois + sèche serviette puissance 500W dans la salle de bain',
                ],
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('information', TextareaType::class, [
                'required' => true,
                'label' => false,
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
            'data_class' => SecondaryHeading::class,
        ]);
    }
}
