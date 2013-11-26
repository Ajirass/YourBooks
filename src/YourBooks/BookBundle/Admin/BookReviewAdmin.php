<?php

namespace YourBooks\BookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BookReviewAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('criteria1', null, array('label' => 'Critère 1 : '))
            ->add('criteria2', null, array('label' => 'Critère 2 : '))
            ->add('criteria3', null, array('label' => 'Critère 3 : '))
            ->add('criteria4', null, array('label' => 'Critère 4 : '))
            ->add('criteria5', null, array('label' => 'Critère 5 : '))
            ->add('critic', null, array('label' => 'Critique : '))
            ->add('problems', null, array('label' => 'Problèmes : '))
            ->add('reader', null, array('label' => 'Lecteur : '))
            ->add('book', null, array('label' => 'Manuscrit : '))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('reader', null, array('label' => 'Lecteur : '))
            ->add('book', null, array('label' => 'Manuscrit : '))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('criteria1', null, array('label' => 'Critère 1 : '))
            ->add('criteria2', null, array('label' => 'Critère 2 : '))
            ->add('criteria3', null, array('label' => 'Critère 3 : '))
            ->add('criteria4', null, array('label' => 'Critère 4 : '))
            ->add('criteria5', null, array('label' => 'Critère 5 : '))
            ->add('critic', null, array('label' => 'Critique : '))
            ->add('problems', null, array('label' => 'Problèmes : '))
            ->add('reader', null, array('label' => 'Lecteur : '))
            ->add('book', null, array('label' => 'Manuscrit : '))
        ;
    }
}