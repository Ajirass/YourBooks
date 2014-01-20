<?php

namespace YourBooks\BookBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre de l\'oeuvre : '))
            ->add('summary', null, array('label' => 'écrivez votre résumé (2000 caractères max) : '))
            ->add('category', null, array('label' => 'Genre : '))
            ->add('file', null, array('label' => 'Téléchargez votre manuscrit : '))
        ;
    }

    public function getName()
    {
        return 'book_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YourBooks\BookBundle\Entity\Book',
        ));

    }
}