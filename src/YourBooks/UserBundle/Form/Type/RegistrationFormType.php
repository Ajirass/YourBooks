<?php

namespace YourBooks\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        //if (isset($options['account']) && ('reader' == $options['account']))
        //{
            $builder
                ->add('siret')
                ->add('fileCV')
                ->add('fileMotivationLetter')
            ;
        //}
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
        return 'yourbooks_user_registration';
    }
}