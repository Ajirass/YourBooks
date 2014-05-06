<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 28/04/2014
 * Time: 13:26
 */

namespace YourBooks\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Collection;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', 'text', array('label' => 'Rechercher un livre'))
        ;
    }

    public function getName()
    {
        return 'search';
    }
}