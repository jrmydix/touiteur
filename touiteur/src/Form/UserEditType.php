<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Photo de profil"
            ])
            ->add('banner', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Bannière du profil"
            ])
            ->add('name', TextType::class, [
                'label' => "Nom"
            ])
            ->add('username', HiddenType::class, [
                'mapped' => false
            ])
            ->add('bio', TextareaType::class, [
                'label' => "Bio"
            ])
            ->add('location', TextType::class, [
                'label' => "Localisation"
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                "attr" => [
                    "class" => "btn"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
