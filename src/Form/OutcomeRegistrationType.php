<?php

namespace App\Form;

use App\Entity\Churches;
use App\Entity\Outcomes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutcomeRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('executedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => ['class' => 'form-control mb-2']
            ]) 
            ->add('motif', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('amount', IntegerType::class, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
/*             ->add('churches', EntityType::class, [
                'class' => Churches::class,
                'choice_label' => 'design',
                'label' => 'Church',
                'attr' => ['class' => 'form-control mb-2'],
            ]) */
            ->add('register', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outcomes::class,
        ]);
    }
}
