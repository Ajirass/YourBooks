<?php

namespace YourBooks\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OutilsAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Titre : ', 'attr'=>array('class'=>'ckeditor')))
            ->add('content', null, array('label' => 'Contenu : ', 'attr'=>array('class'=>'ckeditor')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('content', null, array('label' => 'Contenu : '))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title', null, array('label' => 'Titre : '))
            ->add('content', null, array('label' => 'Contenu : '))
        ;
    }
}