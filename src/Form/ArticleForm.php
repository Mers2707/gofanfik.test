<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\ArticleSection;
use App\Entity\Fanfik;
use App\Form\SectionForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;


class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class,['attr' => ['maxlength' => 255]])
            ->add('fanfik', EntityType::class, array(
                'class' => Fanfik::class,
                'choice_label' => function ($fanfik) {
                    return $fanfik->getName();
                },
                'expanded' => false,
                'multiple' => false,))
            ->add('section_id', CollectionType::class, array(
                'entry_type' => SectionForm::class,
                'entry_options' => array(
                    'attr' => array('class' => 'ArticleSection',),
                    'label' => false,
                ),
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
            ))
            ->add('new_section', ButtonType::class, [
                'attr' => ['class' => 'btn-secondary btn newsection'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Articles::class,
        ));
    }
}