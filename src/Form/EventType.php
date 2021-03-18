<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\CategorieEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;




class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,[
                'attr' => ['placeholder' => 'Name']
                ])
            ->add('description', CKEditorType::class)
            ->add('lieu', null,[
                'attr' => ['placeholder' => 'Lieu']
                ])
            ->add('prix', IntegerType::class ,[
                'attr' => ['placeholder' => 'Prix DT']
                ])
            ->add('img', FileType::class ,[
                'data_class' => null,
                'label' => 'Image' ,
                'attr' => ['placeholder' => 'image.jpg',
                'class'=>"form-control-file"]
                
                
                ])
            ->add('url', UrlType::class,[
                'attr' => ['placeholder' => 'www.exemple.com/exemple/']
                ])
            ->add('dateDebut')        
            ->add('dateFin')    
            ->add('nbParticipants', IntegerType::class ,[
                'attr' => ['placeholder' => 'Nombre de participants']
                ])
            ->add('category', EntityType::class, [
                'class' => CategorieEvent::class,
                'choice_label' => 'name',
            ]);
        
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
