<?php

namespace YourBooks\BookBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre de l\'oeuvre : '))
            ->add('summary', null, array('label' => 'écrivez votre résumé (2000 caractères max) : '))
            ->add('category', null, array('label' => 'Genre : '))
            ->add('terms','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Conditions d\'utilisations : ', 'required' => 'true'))
            ->add('rights','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Je confirme détenir l\'intégralité des droits de ce manuscrit ', 'required' => 'true'))
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