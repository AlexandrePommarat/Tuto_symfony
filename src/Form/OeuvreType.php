<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',     TextType::class)
            ->add('valider',      SubmitType::class)
        /* Rappel :
      ** - 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
    ** - 2e argument : type du champ, ici « CollectionType » qui est une liste de quelque chose
    ** - 3e argument : tableau d'options du champ
      */
            ->add('categories', EntityType::class, array(
                'class'   => 'App\Entity\Category',
                'choice_label'    => 'name',
                'multiple' => true,
                'expanded' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Oeuvre'
        ));
    }
}