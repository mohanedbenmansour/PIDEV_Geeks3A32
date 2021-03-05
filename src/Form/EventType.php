<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,[
                'attr' => ['placeholder' => 'Name']
                ])
            ->add('description', null,[
                'attr' => ['placeholder' => 'Description']
                ])
            ->add('lieu', null,[
                'attr' => ['placeholder' => 'Lieu']
                ])
            ->add('prix', IntegerType::class ,[
                'attr' => ['placeholder' => 'Prix DT']
                ])
            ->add('img', FileType::class ,[
                'label' => 'Image' ,
                'attr' => ['placeholder' => 'image.jpg']
                ])
            ->add('url', null,[
                'attr' => ['placeholder' => 'www.exemple.com/exemple/']
                ])
            ->add('dateEvent')            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
