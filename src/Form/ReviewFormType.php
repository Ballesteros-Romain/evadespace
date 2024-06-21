<?php

namespace App\Form;

use App\Entity\Review;
use App\Entity\User;
use App\Entity\Workspace;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
                'required' => true,
                'help' => 'Entrez une note de 1 à 5',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La note ne peut pas être vide.',
                    ]),
                    new Assert\Choice([
                        'choices' => ['1', '2', '3', '4', '5'],
                        'message' => 'Veuillez sélectionner une note valide.',
                    ]),
                ]
                ])
                
            ->add('message')
            // ->add('author', TextType::class, [
            //     'required' => true,
            // ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'required' => true,
                // 'data' => $options['user'],
                'disabled' => true,
            ])
            ->add('workspace', EntityType::class, [
                'class' => Workspace::class,
                'choice_label' => 'name',
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez choisir un espace de travail.',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            'user' => null
        ]);
    }
}