<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    $builder
        ->add('author', TextType::class)
        ->add('content',TextType::class)
        ->add('note',IntegerType::class)
        ->add('valider',SubmitType::class);
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Avis'
        ));
    }
}