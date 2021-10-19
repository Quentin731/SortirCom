<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSortieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la sortie:'])
            ->add('dateSortie', DateTimeType::class, [
                'label' => 'Date et heure de la sortie:',
                'widget' => 'single_text',
                ])
            ->add('dateLimite', DateType::class, [
                'label' => 'Date limite d\'inscription:',
                'widget' => 'single_text',
                ])
            ->add('nombrePlace', IntegerType::class, ['label' => 'Nombre de places:'])
            ->add('duree', IntegerType::class, ['label' => 'Durée (en minutes):'])
            ->add('description', TextType::class, ['label' => 'Description et infos:'])

            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
