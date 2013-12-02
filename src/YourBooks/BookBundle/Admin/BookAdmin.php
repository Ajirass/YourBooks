<?php

namespace YourBooks\BookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BookAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('category', null, array('label' => 'Catégorie : '))
            ->add('file', 'file')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('enabled', null, array('label' => 'Autorisé par l\'administrateur ?'))
            ->add('sendByReader', null, array('label' => 'Envoyé par le lecteur ?'))
            ->add('readerValidation', null, array('label' => 'Validation lecteur ?'))
            ->add('edited', null, array('label' => 'Édité ?'))
            //->add('author')
            //->add('readers')
            //->add('editor')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title')
            ->add('summary')
            ->add('enabled', null, array('label' => 'Autorisé par l\'administrateur ?', 'editable'=>true))
            ->add('sendByReader', null, array('label' => 'Envoyé par le lecteur ?', 'editable'=>true))
            ->add('readerValidation', null, array('label' => 'Validation lecteur ?', 'editable'=>true))
            ->add('edited', null, array('label' => 'Édité ?', 'editable'=>true))
        ;
    }
}