<?php

namespace App\Form;

use App\Entity\Biens;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('surface')
            ->add('piece')
            ->add('chambre')
            ->add('ville')
            ->add('codePostale')
            ->add('prix')
            ->add('vendu')
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'nom',
                'multiple' => true
            ])
            
            ->add('file', FileType::class, [
                'label' => 'file',
                'required' => false,
                'data_class' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Biens::class,
        ]);
    }
}
