<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label'=> 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('nom', TextType::class, [
                'label'=> 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('sujet', TextType::class, [
                'label'=> 'Sujet de votre message',
                'attr' => [
                    'placeholder' => 'Sujet de votre message'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Votre email',
                'attr' => [
                    'placeholder' => 'Entrez votre email'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=> 'Votre message',
                'attr' => [
                    'placeholder' => 'En quoi puis-je vous aider ?'
                ]
            ])
            ->add('submit', SubmitType::class , [
                'label'=> 'Envoyer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
