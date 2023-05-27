<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Courriel',
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'pseudo',
            ])
            ->add('bio', TextType::class, [
                'label' => 'bio',
            ])
            ->add('avatar', TextType::class, [
                'label' => 'avatar',
            ])

            ->add('roles', ChoiceType::class, [
                // les choix
                'choices' => [
                    // label, valeur
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // $roles = array donc choix multiple
                'multiple' => true,
                // checkboxes (1 widget HTML par choix)
                'expanded' => true,
            ])

            
           ;
            
    }

    /**
     * Ici, on configure les options du form en lui-même
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // équivaut à Twig {'attr': {'novalidate': 'novalidate'}}
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
