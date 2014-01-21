<?php

namespace YourBooks\BookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Tests\Filter\QueryBuilder;
use YourBooks\BookBundle\Entity\BookRepository;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;

class BookAdmin extends Admin
{
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
                'help' => '<a href="/'.$currentBook->getWebPath().'">Download File : '.$currentBook->getFileName().'</a>',
            );

       // $query_user = $this->modelManager->getEntityManager('Application\Sonata\UserBundle\Entity\User‌​')->createQueryBuilder()
         //   ->add('select', 'u')
           // ->add('from', 'Application\Sonata\UserBundle\Entity\User u')
           // ->where($query_user->expr()->in('r.role', 'ROLE_READER'))
           // ->add('orderBy', 'u.username ASC');



        $formMapper
            ->add('title', null, array('label' => 'Titre : '))
            ->add('summary', null, array('label' => 'Résumé : '))
            ->add('category', null, array('label' => 'Catégorie : '))
            ->add('reader', null, array('label' => 'Lecteur : '))
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
            ->add('review', null, array('label' => 'Review : '))
            ->add('readerValidation', null, array('label' => 'Notes validées ?', 'editable' => true))
            ->add('edited', null, array('label' => 'Édité ?', 'editable' => true))
        ;
    }

    public function findByRole() {
        $role = 'ROLE_READER';
        //$queryBuilder = $this->getModelManager()->getEntityManager($this->getClass())->createQueryBuilder();
        $userManager = $this->get('fos_user.user_manager');
        $qb = $userManager->createQueryBuilder();
        $qb->select('u')
            ->from('Sonata\UserBundle\Model\User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');
        return $qb->getQuery()->getResult();
    }

    public function findByRole1()
    {
        return $userManager = $this->getModelManager()->findBy(array('roles' => 'ROLE_READER'));
    }
}