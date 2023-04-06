<?php

namespace App\Form;

use App\Entity\FicheFrais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateLigneFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('forfaitEtape', IntegerType::class, [
                'label' => 'Saisissez votre Forfait Etape',
                'required'=>true,
            ])

            ->add('nuite', IntegerType::class, [
                'label' => 'Saisissez votre Nuite',
                'required'=>true,
            ])

            ->add('repas',IntegerType::class, [
                'label' => 'Saisissez votre Repas',
                'required'=>true,
            ])

            ->add('forfaitKm', IntegerType::class, [
                'label' => 'Saisissez votre Forfait KM',
                'required'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
                ]);
    }

}
