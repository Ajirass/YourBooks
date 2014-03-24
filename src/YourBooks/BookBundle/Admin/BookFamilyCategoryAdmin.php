<?php

namespace YourBooks\BookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BookFamilyCategoryAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('name', null, array('label' => 'Nom : '))
            ->add('colorCategory', 'choice', array('label' => 'Couleur catégorie', 'choices' => array('rouge' => 'rouge', 'bleu' => 'bleu', 'orange' => 'orange', 'vert' => 'vert', 'rose' => 'rose', 'violet' => 'violet', 'bleu-clair' => 'bleu clair')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom : '))
            ->add('colorCategory', null, array('label' => 'Couleur : '))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('id')
            ->add('name', null, array('label' => 'Nom : '))
            ->add('ColorCategory', null, array('label' => 'Couleur Catégorie : '))
            ->add('categories', null, array('label' => 'Catégories Filles :'))
        ;
    }
}