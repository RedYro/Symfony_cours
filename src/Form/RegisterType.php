<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [ // Attributs liés au champ prénom du form
                    'placeholder' => 'Prénom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre prénom'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit avoir au minimum {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre prénom doit avoir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre nom'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit avoir au minimum {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre nom doit avoir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])

            //  1ère méthode avec "mapped = false"
            // ->add('password', PasswordType::class,[
            //     'label' => false,
            //     'attr' => [
            //         'placeholder' => 'Mot de passe',
            //     ],
            // ]) 
            // ->add('confirmPassword', PasswordType::class,[
            //     'label' => false,
            //     'mapped' => false, // Permet de ne pas lier le champ à l'entité
            //     'attr' => [
            //         'placeholder' => 'Confirmer le mot de passe',
            //     ],
            // ]) 

            //---------------------------------------------------------------------------------------------------------
            
            //  2ème méthode sans "mapped = false"
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation de celui-ci ne correspondent pas',
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner votre mot de passe'
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                            'max' => 30,
                            'maxMessage' => 'Votre nom doit avoir au maximum {{ limit }} caractères',
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmer le mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez confirmer votre mot de passe'
                        ]),
                    ],
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
