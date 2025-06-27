<?php

namespace App\Form;

use App\Entity\Prestation;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousForm extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('date', DateTimeType::class, [
            
            'disabled' => $options['date_disabled'] ?? false,
        ])
        
           ->add('prestation', EntityType::class, [
    'class' => Prestation::class,
    'choice_label' => function ($prestation) {
        return $prestation->getNom() . ' (' . $prestation->getDuree() . ' min, ' . $prestation->getPrix() . ' €, ' . $prestation->getDescription() . ')';
    },
    'multiple' => true,
    'expanded' => true,
    'choice_attr' => function ($prestation) {
        return [
            'data-prix' => $prestation->getPrix(),
            'data-duree' => $prestation->getDuree(),
        ];
    },
]);
    }

    public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => RendezVous::class,
        'date_disabled' => false, // option personnalisée
    ]);
}
}