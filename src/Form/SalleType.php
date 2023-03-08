<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_s')
            ->add('image_s', FileType::class, ['mapped' => false]) //hedhi jarab nahi mapped
            ->add('tel_s')
            ->add('email_s')
            ->add('adresse_s')
            ->add('pos1')
            ->add('pos2')
            ->add('ville_s')
            ->add('perimetre_s')
            ->add('abonnements', EntityType::class, [
                'class' => Abonnement::class,
                'choice_label' => 'nom_a',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
