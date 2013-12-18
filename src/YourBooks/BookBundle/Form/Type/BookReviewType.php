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
            ->add('summary')
            ->add('criteria1')
            ->add('criteria2')
            ->add('criteria3')
            ->add('criteria4')
            ->add('criteria5')
            ->add('critic')
            ->add('problems')
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