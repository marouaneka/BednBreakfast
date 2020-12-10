<?php

namespace App\Form;

use App\Entity\Owner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Fixtures\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class OwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
            ->add('familyname', TextType::class, [
                'label' => 'Last name',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Owner::class,
        ]);
    }
}
