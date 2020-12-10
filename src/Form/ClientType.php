<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ClFirstName', TextType::class, [
                'label' => 'First name',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
            ->add('ClFamilyName', TextType::class, [
                'label' => 'Last name',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
            ->add('ClCountry', TextType::class, [
                'label' => 'Country',
                'label_attr' => [ 'class' => 'row-justify-center'],
                'attr' => [ 'class' => 'form-control']
            ])
           /* ->add('FirstName',null,['property_path'=>'ClFirstName'])
            ->add('FamilyName',null,['property_path'=>'ClFamilyName'])
                ->add('Country',null,['property_path'=>'ClCountry'])*/
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
