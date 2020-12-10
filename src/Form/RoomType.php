<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('imageName', TextType::class,  [
            'label' => 'Image name',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control'],
            'required' => false
        ])
        ->add('imageFile', VichImageType::class, [
            'label' => 'Choose an image',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control-file'],
            'required' => false
        ])
        ->add('summary', TextType::class, [
            'label' => 'Summary',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('description', TextType::class, [
            'label' => 'Description',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('capacity', IntegerType::class,[
            'label' => 'Capacity',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('superficy',NumberType::class,[
            'label' => 'Superficy',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('price', NumberType::class,[
            'label' => 'Price',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('address', TextType::class, [
            'label' => 'Address',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ->add('regions', EntityType::class ,[
            'class' => Region::class,
            'choice_label' => 'name',
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
            'data_class' => Room::class,
        ]);
    }
}
