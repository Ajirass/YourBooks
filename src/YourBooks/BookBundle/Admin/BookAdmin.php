<?php

namespace YourBooks\BookBundle\Admin;

use Application\Sonata\UserBundle\Entity\User;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Tests\Filter\QueryBuilder;
use YourBooks\BookBundle\Entity\BookRepository;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class BookAdmin extends Admin
{

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        //$userManager = $this->container->get('fos_user.user_manager');
        //$reader = $userManager->findUserBy(array('roles' => 'ROLE_READER'));
        //$userManager = $this->get('fos_user.user_manager');
        //$this->getModelManager()->createQuery('Application\Sonata\UserBundle\Entity\User', 'u');
        //$query = $this->getModelManager()->getEntityManager('Application\Sonata\UserBundle\Entity\User')->createQueryBuilder()
        //    ->select('u.username')
        //    ->add('from', 'Application\Sonata\UserBundle\Entity\User')
        //    ->add('where', 'u.roles = ROLE_READER');
        //$readers = $qb->getQuery()->getResult();
        static $options = array();
        $currentBook = $this->getSubject();
        if (null !== $currentBook->getFileName())
            $options = array(
                'required' => false,
                'help' => '<a href="/YourBooks/web/'.$currentBook->getWebPath().'">Download File : '.$currentBook->getFileName().'</a>',
            );

       // $query_user = $this->modelManager->getEntityManager('Application\Sonata\UserBundle\Entity\User‌​')->createQueryBuilder()
         //   ->add('select', 'u')
           // ->add('from', 'Application\Sonata\UserBundle\Entity\User u')
           // ->where($query_user->expr()->in('r.role', 'ROLE_READER'))
           // ->add('orderBy', 'u.username ASC');



        $formMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('author', null, array(
                'class' => 'ApplicationSonataUserBundle:User',
                'label' => 'Auteur : ',
                'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('u');
                        $qb->where($qb->expr()->like('u.roles', $qb->expr()->literal('%ROLE_AUTHOR%')));
                        return $qb;
                    }
            ))
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('category', null, array('label' => 'Catégorie : '))
            ->add('reader', null, array(
                'class' => 'ApplicationSonataUserBundle:User',
                'label' => 'Lecteur : ',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('u');
                    $qb->where($qb->expr()->like('u.roles', $qb->expr()->literal('%ROLE_READER%')));
                    return $qb;
                }
            ))
            ->add('file', 'file', $options)
            ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('author', null, array('label' => 'Auteur : '))
            ->add('retracted', null, array('label' => 'Auteur rétracté ?'))
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
            ->add('retracted', null, array('label' => 'Auteur rétracté ?', 'editable' => true))
            ->add('enabled', null, array('label' => 'Autorisé par l\'administrateur ?', 'editable' => true))
            ->add('receivedByReader', null, array('label' => 'reçu par le lecteur ?', 'editable' => true))
            ->add('sendByReader', null, array('label' => 'Notes envoyées ?', 'editable' => true))
            ->add('review', null, array('label' => 'Review : '))
            ->add('readerValidation', null, array('label' => 'Notes validées ?', 'editable' => true))
            ->add('edited', null, array('label' => 'Édité ?', 'editable' => true))
        ;
    }
}