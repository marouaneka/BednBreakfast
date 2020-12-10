<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Region name',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('presentation', TextType::class, [
            'label' => 'Presentation',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('country', TextType::class, [
            'label' => 'Country',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
            ->add('room', EntityType::class ,[
                'class' => Room::class,
                'choice_label' => 'summary',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => [ 'class' => 'form-check-label checkboxLabel'],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input checkboxX'];
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
