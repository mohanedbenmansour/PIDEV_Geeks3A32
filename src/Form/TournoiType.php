<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tournoi;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('description')
            ->add('category', EntityType::class, [
                    // looks for choices from this entity
                    'class' => Category::class,

                    'choice_label' => 'Type',
                    'attr'=>[
                        'class'=>"form-control"
                    ]

                ]

            )
            ->add('adresse')
            ->add('date_tournoi')
            ->add('Nb_max')

            ->add('imageFile', FileType::class, [
                'mapped' => false
            ])
            ->add ('submit',SubmitType::class,
                ['attr'=>['formnonvalidate'=>'formnonvalidate']])





        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
