<?php

namespace YourBooks\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Tests\Filter\QueryBuilder;
use Sonata\UserBundle\Model\User;
use YourBooks\BookBundle\Entity\BookRepository;

class FooterAdmin extends Admin
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

        $formMapper
            ->add('presse', null, array('label' => 'Espace Presse : '))
            ->add('team', null, array('label' => 'L\'équipe YourBooks : '))
            ->add('charte', null, array('label' => 'Charte d\'écriture : '))
            ->add('engagements', null, array('label' => 'Engagements '))
            ->add('partenaires', null, array('label' => 'Partenaires '))
            ->add('mentionslegales', null, array('label' => 'Mentions Légales : '))
            ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('presse', null, array('label' => 'Espace Presse : '))
            ->add('team', null, array('label' => 'L\'équipe YourBooks : '))
            ->add('charte', null, array('label' => 'Charte d\'écriture : '))
            ->add('engagements', null, array('label' => 'Engagements '))
            ->add('partenaires', null, array('label' => 'Partenaires '))
            ->add('mentionslegales', null, array('label' => 'Mentions Légales : '))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('presse', null, array('label' => 'Espace Presse : '))
            ->add('team', null, array('label' => 'L\'équipe YourBooks : '))
            ->add('charte', null, array('label' => 'Charte d\'écriture : '))
            ->add('engagements', null, array('label' => 'Engagements '))
            ->add('partenaires', null, array('label' => 'Partenaires '))
            ->add('mentionslegales', null, array('label' => 'Mentions Légales : '))
        ;
    }
}