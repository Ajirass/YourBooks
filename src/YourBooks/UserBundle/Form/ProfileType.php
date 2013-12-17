<?php

namespace YourBooks\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm Password'),
            ))
        ;
    }

    public function addEditorFields(FormBuilderInterface $builder)
    {
        $builder
            ->add('company')
        ;

        return $builder;
    }

    public function addReaderFields(FormBuilderInterface $builder)
    {
        $builder
            ->add('siret')
            ->add('fileCV')
            ->add('fileMotivationLetter')
        ;

        return $builder;
    }

    public function getName()
    {
        return 'profile_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
        ));

    }
}