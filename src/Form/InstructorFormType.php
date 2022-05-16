<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Instructor;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstructorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username'
            ])
            ->add('department',EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instructor::class,
        ]);
    }
}
