<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('Stars', IntegerType::class,[
            'label' => 'Rate this room (1 to 5 stars)',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        
        ->add('Description', TextareaType::class, [
            'label' => 'Give a description',
            'label_attr' => [ 'class' => 'row-justify-center'],
            'attr' => [ 'class' => 'form-control']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
