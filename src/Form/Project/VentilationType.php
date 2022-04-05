<?php

namespace App\Form\Project;

use App\Entity\Project\Ventilation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentilationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('systems', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Simple flux hydro basse consommation' => 'Simple flux hydro basse consommation',
                    'Simple flux classique' => 'Simple flux classique',
                    'Double flux auto' => 'Double flux auto',
                    'Double flux hydro' => 'Double flux hydro',
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
            'data_class' => Ventilation::class,
        ]);
    }
}
