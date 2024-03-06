<?php

namespace App\Form;

use App\Entity\Utilisateur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class'=>'input'],
                
                'label' => 'Saisir votre nom : ',
                'label_attr' => ['class' => 'label'],
                'required' => true,
            ])
            //Toutes ces informations sont liées à la documentation
            ->add('prenom', TextType::class, [
                'attr' => ['class'=>'input'],
                
                'label' => 'Saisir votre prénom : ',
                'label_attr' => ['class' => 'label'],
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'attr' => ['class'=>'input'],
                
                'label' => 'Saisir votre email : ',
                'label_attr' => ['class' => 'label'],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                //Il est l'un des rares champs qui peut être null
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('image', TextType::class, [
                'attr' => ['class'=>'input'],
                // 'empty_data' => '',
                'label' => 'Saisir votre image : ',
                'label_attr' => ['class' => 'label'],
                'required' => false,
                
            ])
            // ->add('titre', TextType::class,[
            //     'attr'=>['class'=>'form'],
            //     'required'=>true
            //     ])
            // ->add('contenu', TextareaType::class)
            // ->add('date', DateType::class)
            // ->add('categories', EntityType::class,
            //     [
            //     // looks for choices from this entity
            //     'class' => Categorie::class,
            //     'label' => 'Catégories :',
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            //     'expanded' => false,
            //     'required' => true
            //     ]
            //     )
            // ->add('user', EntityType::class,
            //     [
            //     // looks for choices from this entity
            //     'class' => Utilisateur::class,
            //     'label' => 'Utilisateurs :'
            //     ]
            //     )
            // ->add('Envoyer', SubmitType::class)
            //ajout d'un attribut non mappé
            // ->add('age', NumberType::class, [
            //     'mapped'=>false
            //      ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
