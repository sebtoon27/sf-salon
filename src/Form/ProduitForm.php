<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ProduitForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('nom')
        
        ->add('date_debut', null, [
            'widget' => 'single_text'
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text'
                ])
                ->add('description')
                
                ->add('prix') 
                
                
                ->add('image1', FileType::class, [
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
                                'image/avif',
                            ],
                            'mimeTypesMessage'=>'Extension de fichier invalide',
                            ])
                        ],
                        'attr'=>[
                            'class'=>'form-control',
                        ],
                        ])
                        ->add('image2', FileType::class, [
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
                                        'image/avif',
                                    ],
                                    'mimeTypesMessage'=>'Extension de fichier invalide',
                                    ])
                                ],
                                'attr'=>[
                                    'class'=>'form-control',
                                ],
                                ])
                                
                                
                                ->add('image3', FileType::class, [
                                    'mapped' => false,
                                    'required' => false,
                                    'constraints' => [
                                        new File([
                                            'maxSize' => '16384k',
                                            'maxSizeMessage' => 'Taille de fichier trop grande',
                                            'mimeTypes' => [
                                                'image/jpeg',
                                                'image/jpg',
                                                'image/png',
                                                'image/gif',
                                                'image/webp',
                                                'image/svg',
                                                'image/bmp',
                                                'image/avif',
                                            ],
                                            'mimeTypesMessage' => 'Extension de fichier invalide',
                                            ])
                                        ],
                                        'attr' => [
                                            'class' => 'form-control',
                                        ],
                                        ])
                                        ;
                                    }
                                    
                                    public function configureOptions(OptionsResolver $resolver): void
                                    {
                                        $resolver->setDefaults([
                                            'data_class' => Produit::class,
                                        ]);
                                    }
                                }
 ?>                               
                                </div>
                                </div>