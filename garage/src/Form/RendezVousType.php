<?php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jour', TextType::class,[
                'label' => 'Date',
                'attr' => [
                    'class' => 'datepicker-here input',
                    'id' => 'test',
                    'data-timepicker' => 'true',
                    'data-language' => 'fr'

                ]
            ])
            ->add('sujet',TextType::class,[
                'attr' =>['placeholder' => 'Ex:Revision,vidange...',
                    'class' => 'input']
            ])
        ->add('message', TextareaType::class,[
            'attr' =>['placeholder' => 'Decrivez votre problème',
                'class' => 'input']
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
