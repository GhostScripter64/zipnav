<?php

namespace App\Form;

use App\Entity\Airport;
use App\Entity\Flight;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', EntityType::class, [
              'class' => Airport::class,
              'attr' => array('class' => 'form-control')
            ])
            ->add('to', EntityType::class, [
              'class' => Airport::class,
              'attr' => array('class' => 'form-control')
            ])
            ->add('kfm', TextareaType::class, [
              'attr' => array('class' => 'form-control')
            ])
            ->add('save', SubmitType::class, [
              'label' => 'Save',
              'attr' => array('class' => 'btn btn-primary mt-3')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
        ]);
    }
}
