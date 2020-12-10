<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('DateDebut', DateType::class, [
            'data' => new \DateTime(),
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'label_attr' => array('class' => 'col-lg-6 offset-md-3 text-center'),
            'label' => 'Choose date of start of your stay', 
            'attr' => array('class' => 'form-control')
        ])
        ->add('DateFin', DateType::class, array(
            'data' => new \DateTime(),
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'label_attr' => array('class' => 'col-lg-6 offset-md-3 text-center'),
            'label' => 'Choose date of end of your stay', 
            'attr' => array('class' => 'form-control')
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
