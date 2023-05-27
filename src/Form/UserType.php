<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Adresse e-mail',
            'attr'=>[
                "placeholder"=>"exemple@exemple.com"
            ],
        ])
        ->add('pseudo', TextType::class, [
            'label' => 'Pseudo',
            'attr'=>[
                "placeholder"=>"Pseudo"
            ],
        ])
        ->add('bio', TextareaType::class, [
            'label' => 'Biographie',
            'attr'=>[
                "placeholder"=>"Biographie"
            ],
        ])
        ->add('avatar', UrlType::class, [
            'label' => 'Avatar',
            'attr'=>[
                "placeholder"=>"http://avatar.jpeg"
            ],
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
        ]);
        // J'utilise une option custom du formulaire pour faire un affichage conditonnel
        // if($options["custom_option"] !== "edit"){
            $builder
                ->add('password',RepeatedType::class,[
                    "type" => PasswordType::class,
                    'invalid_message' => 'Les deux champs doivent être identique',
                    'required' => true,
                    'first_options'  => ['label' => 'Le mot de passe',"attr" => ["placeholder" => "*****"]],
                    'second_options' => ['label' => 'Répétez le mot de passe',"attr" => ["placeholder" => "*****"]],
                ]);
            // }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
