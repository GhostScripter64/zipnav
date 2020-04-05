<?php

namespace App\Form;

use App\Entity\Airport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AirportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = get_class_vars(Airport::class);

        $builder
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('region', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Airport::class,
        ]);
    }
}
