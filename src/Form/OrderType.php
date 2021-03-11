<?php

namespace App\Form;

use App\Entity\Order;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('userAdress',TextType::class,[
                'label'=>"street adress",
                'attr'=>[
                    "placeholder"=>"street and number,appartement",
                    'class'=>"form-control"
                ]
            ])
            ->add('userPhone',IntegerType::class,[
                'label'=>"phone",
                'attr'=>[
                    "placeholder"=>"enter you phone number",
                    'class'=>"form-control"
                ]
            ])

            ->add('city',IntegerType::class,[
                'label'=>"city",
                'attr'=>[
                    "placeholder"=>"",
                    'class'=>"form-control"
                ]
            ])
            ->add('state',IntegerType::class,[
                'label'=>"state/province/region",
                'attr'=>[
                    "placeholder"=>"",
                    'class'=>"form-control"
                ]
            ])
            ->add('zipcode',IntegerType::class,[
                'label'=>"zipcode",
                'attr'=>[
                    "placeholder"=>"",
                    'class'=>"form-control"
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
