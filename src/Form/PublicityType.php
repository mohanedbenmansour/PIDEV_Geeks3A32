<?php

namespace App\Form;

use App\Entity\Publicite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PublicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextType::class,[
                'label'=>"Content",])
            ->add('date_debut',DateType::class,[
                'label'=>"Period Start Date",])
            ->add('date_fin',DateType::class,[
                'label'=>"Period End Date",])
            ->add('imagefilename',FileType::class,[
                'label'=>"Image",
                'mapped' => false,
                'attr'=>[

                    'class'=>"form-control-file",

            ]])
            ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publicite::class,
        ]);
    }
}
