<?php

namespace YourBooks\BookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BookStatusAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Nom : '))
            ->add('type', null, array('label' => 'Type : '))
            ->add('user', null, array('label' => 'Utilisateur : '))
            ->add('books', null, array('label' => 'Manuscrit : '))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Nom : '))
            ->add('type', null, array('label' => 'Type : '))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title', null, array('label' => 'Nom : '))
            ->add('type', null, array('label' => 'Type : '))
            ->add('user', null, array('label' => 'User : '))
            ->add('books', null, array('label' => 'Manuscrit : '))
        ;
    }
}