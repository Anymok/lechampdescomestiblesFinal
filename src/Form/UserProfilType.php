<?php

namespace App\Form;

use App\Entity\UserProfil;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', null, [
                'label' => 'Prénom'
            ])
            ->add('nom', null, [
                'label' => 'Nom'
            ])
            ->add('mail', null, [
                'label' => 'Adresse e-mail'
            ])
            ->add('tel', null, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('Ville', null, [
                'label' => 'Ville'
            ])
            ->add('Pays', null, [
                'label' => 'Pays'
            ])
            ->add('CP', null, [
                'label' => 'Code Postal'
            ])
            ->add('Rue', null, [
                'label' => 'Rue'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfil::class,
        ]);
    }
}
