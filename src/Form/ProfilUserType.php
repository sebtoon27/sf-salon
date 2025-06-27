<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ProfilUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
     
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Adresse postale',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Numéro de téléphone',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])


                           ->add('avatar', FileType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}