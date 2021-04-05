<?php

namespace App\Form;

use App\EventSubscriber\UserPasswordEncoderSubscriber;
use App\Entity\User;
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
    private $userPasswordEncoderSubscriber;

    public function __construct(UserPasswordEncoderSubscriber $userPasswordEncoderSubscriber)
    {
        $this->userPasswordEncoderSubscriber = $userPasswordEncoderSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('alias', TextType::class, [
                'label'=> 'Pseudonyme',
                'constraints' => new Length(null,2,30),
                'attr' => [
                    'placeholder' => 'votre pseudo'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'Prénom',
                'constraints' => new Length(null,2,30),
                'attr' => [
                    'placeholder' => 'votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Nom',
                'constraints' => new Length(null,2,30),
                'attr' => [
                    'placeholder' => 'votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Email',
                'attr' => [
                    'placeholder' => 'votre adresse email'
                ]

            ])
            ->add('plaintextPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent etre identiques !',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label'=> 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Choisir un mot de passe'
                    ]

                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe'
                    ]
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> "S'inscrire"
            ])
        ;
        $builder->addEventSubscriber($this->userPasswordEncoderSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
