<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//ajout du use pour utiliser le type input password de Symfony
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                'label'=>"username",
                'attr'=>[
                    "placeholder"=>"add name",
                    'class'=>"form-control" ]
                ])
            ->add('nom',TextType::class,[
                'label'=>"username",
                'attr'=>[
                    "placeholder"=>"nom",
                    'class'=>"form-control" ]
            ])
            ->add('prenom',TextType::class,[
                'label'=>"prenom",
                'attr'=>[
                    "placeholder"=>"prenom",
                    'class'=>"form-control" ]
            ])
            ->add('facebookprofil',TextType::class,[
                'label'=>"username",
                'attr'=>[
                    "placeholder"=>"add name",
                    'class'=>"form-control" ]
            ])
            ->add('twitchProfil')
            ->add('youtubeChannel')
            ->add('email',EmailType::class)
            ->add('phoneNumber')
            
            // suppression du role qui sera défini par défaut
            ->add('password', PasswordType::class
            )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}