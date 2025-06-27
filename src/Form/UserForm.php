<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
             
            
            ->add('roles', ChoiceType::class, [
            'label' => 'Roles',
            'label_attr' => [
                'class' => 'form-label'
            ],
            'attr' => [
                'class' => 'form-control'
            ],

            'choices' =>  [
'Administrateur' => "ROLE_ADMIN",
"Utilisateur" => "ROLE_USER"
            ],

            'multiple' => true,
    

        ])
            // ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telephone')
            ->add('statut_premium')
            // ->add('image')
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
