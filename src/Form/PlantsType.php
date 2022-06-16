<?php

namespace App\Form;

use App\Entity\Plants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('description', null, [
                'label' => 'Description'
            ])
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('type', null, [
                'label' => 'Type'
            ])
            ->add('color', null, [
                'label' => 'Couleur'
            ])
            ->add('quantity', null, [
                'label' => 'Quantité'
            ])
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plants::class,
        ]);
    }
}
