<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Projets;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('Description')
            ->add('WalletAddress')
            ->add('DateDeCreation', HiddenType::class, [
                'data' => (new \DateTime())->format('Y-m-d H:i:s'), // Set default value to current date and time
            ])
            ->add('photoURL')
            ->add('category', EntityType::class, [
                'class' => Category::class,
'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
