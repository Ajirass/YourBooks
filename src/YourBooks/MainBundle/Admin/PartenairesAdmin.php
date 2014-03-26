<?php

namespace YourBooks\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PartenairesAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom', null, array('label' => 'Nom : '))
            ->add('prenom', null, array('label' => 'Prénom  (Lecteur) : '))
            ->add('fonction', null, array('label' => 'Fonction (Lecteur) : '))
            ->add('statut', 'choice', array('label' => 'Statut : ', 'choices' => array('editor' => 'Éditeur', 'reader' => 'Lecteur')))
            ->add('file', 'file', array('label'=> 'Photo (Éditeur, Dimensions conseillées : 200x200) : ', 'required' =>false))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('statut', null, array('label' => 'Statut : '))
            ->add('prenom', null, array('label' => 'Prénom : '))
            ->add('nom', null, array('label' => 'Nom : '))
            ->add('fonction', null, array('label' => 'Fonction : '))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('prenom', null, array('label' => 'Prénom : '))
            ->add('nom', null, array('label' => 'Nom : '))
            ->add('fonction', null, array('label' => 'Fonction : '))
            ->add('statut', null, array('label' => 'Statut : '))
        ;
    }
}