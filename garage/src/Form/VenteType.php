<?php

namespace App\Form;

use App\Entity\Vente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label'=> false ,
                'attr' => ['placeholder' => 'titre']
            ])
            ->add('image', FileType::class, ['label' => false,
                'mapped' => false,
                'required' => false,
                ])
            ->add('image2',FileType::class,['label' => false,
                'mapped' => false,
                'required' => false])

            ->add('image3', FileType::class,[
                'label' =>false,
                'mapped' =>false,
                'required' =>false
            ])

            ->add('marque', TextType::class, ['label'=> false,
                'attr' => ['placeholder' => 'marque']
            ])
            ->add('modele', TextType::class, ['label'=> false,
                'attr' => ['placeholder' => 'Modele']
            ])
            ->add('annee', NumberType::class,['label'=> false ,'attr' => ['placeholder' => 'Année']])

            ->add('kilometrage',NumberType::class,
                ['label'=> false,'attr' => ['placeholder' => 'KM']])

            ->add('carburant',TextType::class,['label'=> false,
                'attr' =>[ 'placeholder' => 'carburant']
            ])
            ->add('boiteDeVitesse', ChoiceType::class, [
                'label' => false ,
                'choices' => [
                    'Boite automatique' => 'boite automatique',
                    'Boite manuel' => 'boite manuel'
                ]
            ])
            ->add('prix',NumberType::class,['label'=> false ,'attr' => ['placeholder' => 'Prix']])

        ->add('content',TextareaType::class,[ 'label'=> false,'attr' => ['placeholder' => 'information complementaire']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}
