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
                'label'=>"adress",
                'attr'=>[
                    "placeholder"=>"enter your adress",
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
