<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('adresse')
            ->add('telephone')
            // ->add('agreeTerms', CheckboxType::class, [
            //                     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'Entrer votre mail',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                             
                'mapped' => false,
                'attr' => ['autocomplete' => 'Nouveau mot de pass'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre mot de pass',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de pass doit contenir {{ limit }} caractéres ',
                       
                        'max' => 4096,
                    ]),
                ],
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
        
        // public function configureOptions(OptionsResolver $resolver): void
        // {
        //     $resolver->setDefaults([
        //         'data_class' => User::class,
        //     ]);
        // }
    }
    