<?php

namespace App\Form\Project;

use App\Entity\Project\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('company', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('postalCode', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 144,
                ]),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('projectName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('masterJob', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'ARCHITECTE' => 'ARCHITECTE',
                    "MAITRE D'OEUVRE" => "MAITRE D'OEUVRE",
                    'CONSTRUCTEUR' => 'CONSTRUCTEUR',
                ],
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('cadastralReference', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('projectLocation', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'RASE CAMPAGNE' => 'RASE CAMPAGNE',
                    'VILLAGE' => 'VILLAGE',
                    'CENTRE VILLE' => 'CENTRE VILLE',
                    'ZONE PAVILLONNAIRE' => 'ZONE PAVILLONNAIRE',
                ],
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('projectType', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'CONSTRUCTION' => 'CONSTRUCTION',
                    'EXTENSION' => 'EXTENSION',
                ],
                'attr' => [
                    'class' => 'form-control',
                ], ])
            ->add('constructionPlanDate', DateType::class, [
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
            'data_class' => Project::class,
        ]);
    }
}
