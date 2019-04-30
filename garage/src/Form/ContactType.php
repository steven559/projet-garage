<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['label' => false,
                'attr' =>['placeholder' => 'nom',
                    'class' => 'test1'] ,
            ])
            ->add('email', EmailType::class,['label' => false,
                'attr' =>['placeholder'=>'Email',
                    'class' => 'test1']])
            ->add('sujet', TextType::class ,['label' =>false ,
                'attr' =>['placeholder' =>'Sujet du Messsage',
                    'class' => 'test1']])
            ->add('message',TextareaType::class,['label' =>false ,
                'attr' => ['placeholder' =>'Message',
                    'class' => 'test1'
                    ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
