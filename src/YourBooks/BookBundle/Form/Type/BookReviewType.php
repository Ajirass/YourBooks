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
            ->add('summary', null, array('label' => 'resumé 2000 caractères max : '))
            ->add('criteria1', null, array('label' => 'Style d\'écriture : '))
            ->add('criteria2', null, array('label' => 'Péripéties : '))
            ->add('criteria3', null, array('label' => 'Interêt : '))
            ->add('criteria4', null, array('label' => 'Comprehension : '))
            ->add('criteria5', null, array('label' => 'Dénoument : '))
            ->add('critic', null, array('label' => 'Analyse objective : '))
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