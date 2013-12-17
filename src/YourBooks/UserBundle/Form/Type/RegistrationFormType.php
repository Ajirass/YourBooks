<?php

namespace YourBooks\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //$builder->add('name');
        if ('author' === $options['account_type'])
            $builder->add('dateOfBirth');
    }

    public function getName()
    {
        return 'yourbooks_user_registration';
    }
}