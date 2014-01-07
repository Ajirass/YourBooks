<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 03/01/2014
 * Time: 00:48
 */

namespace YourBooks\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);

        $builder
            ->add('firstname', null, array('label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastname', null, array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle'))
            ->add('phone', null, array('label' => 'form.phone', 'translation_domain' => 'FOSUserBundle'))
            ->add('dateOfBirth', 'birthday', array('label' => 'form.dateOfBirth', 'translation_domain' => 'FOSUserBundle'))
            ->add('biography', 'textarea', array('label' => 'form.biography', 'translation_domain' => 'FOSUserBundle'))
            ->add('street', null, array('label' => 'form.street', 'translation_domain' => 'FOSUserBundle'))
            ->add('zipcode', null, array('label' => 'form.zipcode', 'translation_domain' => 'FOSUserBundle'))
            ->add('city', 'text', array('label' => 'form.city', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getName()
    {
        return 'yourbooks_user_profile';
    }
} 