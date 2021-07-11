<?php

namespace App\Form;

use App\Entity\ArticleSection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class SectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['attr' => ['maxlength' => 255]])
            ->add('number', IntegerType::class, [
                'mapped' => true,
                'required' => true,
                'constraints' => [new Positive()],
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('text', TextareaType::class)
            ->add('delete_section', ButtonType::class, [
                'attr' => ['class' => 'btn-secondary btn deletesection'],
            ]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ArticleSection::class,
        ));
    }
}