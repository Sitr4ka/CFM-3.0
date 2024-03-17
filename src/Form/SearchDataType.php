<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-4 mb-2',
                    'placeholder' => 'Keyword',
                ],
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'All' => 'All',
                    'Incomes' => 'Incomes',
                    'Outcomes' => 'Outcomes',
                ],
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('startDate', DateType::class, [
                'attr' => ['class' => 'form-control mb-2'],
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'attr' => ['class' => 'form-control mb-2'],
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['class' => ' form-control btn btn-primary']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
