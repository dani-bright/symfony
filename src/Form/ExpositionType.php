<?php

namespace App\Form;

use App\Entity\Exposition;
use App\EventSubscriber\Form\HandleImageFieldFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExpositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire'
                    ]),
                ]
            ])
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'la description doit comporter {{limit}} caractère minimum'
                    ])
                ]
            ]);
        $builder->addEventSubscriber((new HandleImageFieldFormSubscriber()));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exposition::class,
        ]);
    }
}
