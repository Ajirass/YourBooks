<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 28/04/2014
 * Time: 20:28
 */

namespace YourBooks\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Collection;

class CategorySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alphabetic', 'choice', array(
                'choices' => array('asc' => 'Ordre croissant', 'desc' => 'Ordre décroissant'),
                'preferred_choices' => array('asc'), 'label' => 'Ordre alphabétique'))
            ->add('note', 'choice', array(
                'choices' => array('asc' => 'Ordre croissant', 'desc' => 'Ordre décroissant'),
                'preferred_choices' => array('asc'), 'label' => 'Note'))
            ->add('date', 'choice', array(
                'choices' => array('asc' => 'Ordre croissant', 'desc' => 'Ordre décroissant'),
                'preferred_choices' => array('asc'), 'label' => 'Date'))
        ;
    }

    public function getName()
    {
        return 'CategorySearch';
    }
} 