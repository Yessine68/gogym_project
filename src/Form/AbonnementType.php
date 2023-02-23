<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_a')
            ->add('type_a')
            ->add('description_a', TextareaType::class)
            ->add('prix_a')
            ->add('debut_a')
            ->add('fin_a')
            ->add('salle_a', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom_s',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
