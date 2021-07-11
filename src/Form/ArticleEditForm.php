<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Fanfik;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('fanfik')
            ->add('section_id', CollectionType::class, array(
                'entry_type' => SectionForm::class,
                'label' => 'Sections',
                'entry_options' => array(
                    'attr' => array('class' => 'ArticleSection'),
                ),
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Articles::class,
        ));
    }
}