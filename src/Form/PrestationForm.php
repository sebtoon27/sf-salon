<?php

namespace App\Form;

use App\Entity\Prestation;
use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PrestationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('duree')
            ->add('prix')
            ->add('description')
            ->add('image', FileType::class, [
'mapped' => false,
'required' => false,
'constraints' => [
new File([
'maxSize'=>'16384k',
'maxSizeMessage'=>'Taille de fichier trop grande',
'mimeTypes'=>[
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                            'image/svg',
                            'image/bmp',
],
'mimeTypesMessage'=>'Extension de fichier invalide',
])
],
'attr'=>[
'class'=>'form-control',
],
])
            // ->add('data')
            // ->add('rendezVouses', EntityType::class, [
            //     'class' => RendezVous::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
