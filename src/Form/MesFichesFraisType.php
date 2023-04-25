<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesFichesFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('liste_mois',ChoiceType::class,[
                'choices' => $options['liste_mois'],
        ])
            ->add('Valide', SubmitType::class,[
                'label' => 'Valider',
                'attr'=>[
                    'class' => 'btn btn-primary'
                ]
            ])
            ->add('Creer', ButtonType::class, [
                'label' => 'Nouvelle fiche de frais forfait',
                'attr' => [
                    'onclick' => 'window.location.href="/listemois/newFiche"',
                    'class' => 'btn btn-success'
                ]
            ]);

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'liste_mois' => [],
            // Configure your form options here
        ]);
    }
}
