<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, ['label' => 'Email: '])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => 'mot de passe',])
            ->add('nom')
            ->add('prenom')
            ->add('numero')
            ->add('Role', ChoiceType::class, [
        'label' => 'Attribution de rôle: ',
        'choices' => [
            'Administrateur' => 'ROLE_ADMIN',
            'Utilisateur simple' => 'ROLE_USER'
        ]
    ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
