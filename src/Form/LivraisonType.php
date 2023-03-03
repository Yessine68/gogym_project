<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\Livreur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_livraison')
            ->add('etat_livraison')
            ->add('Adresse_livraison')
            ->add('idLivreur',EntityType::class, [
                'class' => Livreur::class,
                'choice_label' => 'nom_liv',
            ])
            /*->add('Envoyer',SubmitType::class)*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
