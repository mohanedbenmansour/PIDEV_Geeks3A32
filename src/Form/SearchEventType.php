<?php

namespace App\Form;

use App\Entity\CategorieEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots',SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'search',
                ],
                'required' => false

            ])
            ->add('category',EntityType::class, [
                'class' => CategorieEvent::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                    ],
                'required' => false
            ])
            ->add('Search',SubmitType::class,[
                'attr' => [
                    'class' => 'btn reset-btn btn-primary btn-md-2',
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
