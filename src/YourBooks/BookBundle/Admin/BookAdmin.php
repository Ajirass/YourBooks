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
        /*
        $options = array('required' => false);
        if (($subject = $this->getSubject()) && $subject->getPhoto()) {
            $path = $subject->getPhotoWebPath();
            $options['help'] = '<img src="' . $path . '" />';
        }
        */
        static $options = array();
        $currentBook = $this->getSubject();
        if (null !== $currentBook->getFileName())
            $options = array(
                'required' => false,
                'help' => '<a href="/'.$currentBook->getWebPath().'">Download File : '.$currentBook->getFileName().'</a>',
            );

        $formMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('category', null, array('label' => 'Catégorie : '))
            ->add('readers', null, array('label' => 'Lecteur(s) : '))
            ->add('file', 'file', $options)
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('author', null, array('label' => 'Auteur : '))
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
            ->addIdentifier('createdAt', null, array('label' => 'Crée le'))
            ->add('title')
            ->add('author')
            ->add('enabled', null, array('label' => 'Autorisé par l\'administrateur ?', 'editable' => true))
            ->add('receivedByReader', null, array('label' => 'reçu par le lecteur ?', 'editable' => true))
            ->add('sendByReader', null, array('label' => 'Notes envoyées ?', 'editable' => true))
            ->add('readerValidation', null, array('label' => 'Notes validées ?', 'editable' => true))
            ->add('edited', null, array('label' => 'Édité ?', 'editable' => true))
        ;
    }
}