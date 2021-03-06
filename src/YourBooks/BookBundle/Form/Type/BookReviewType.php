<?php

namespace YourBooks\BookBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', null, array('label' => 'Résumé (2000 caractères max) : '))
            ->add('criteria1', 'choice', array(
                'choices'   => array(
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                ),
                'label' => 'Impression Générale',
                'multiple'  => false,
                'expanded' => true,
            ))
            ->add('criteria2', 'choice', array(
                'choices'   => array(
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                ),
                'label' => 'Intrigue/Histoire',
                'multiple'  => false,
                'expanded' => true,
            ))
            ->add('criteria3', 'choice', array(
                'choices'   => array(
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                ),
                'label' => 'Intérêt/Sujet',
                'multiple'  => false,
                'expanded' => true,
            ))
            ->add('criteria4', 'choice', array(
                'choices'   => array(
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                ),
                'label' => 'Editing',
                'multiple'  => false,
                'expanded' => true,
            ))
            ->add('criteria5', 'choice', array(
                'choices'   => array(
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                ),
                'label' => 'Pertinence/Attrait',
                'multiple'  => false,
                'expanded' => true,
            ))
            ->add('critic', null, array('label' => 'Analyse objective (200 caractères max) :  '))
            ->add('problems', null, array('label' => 'Problèmes éventuels : '))
        ;
    }

    public function getName()
    {
        return 'book_review_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YourBooks\BookBundle\Entity\BookReview',
        ));

    }
}