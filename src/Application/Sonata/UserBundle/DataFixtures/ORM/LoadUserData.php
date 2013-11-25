<?php

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEmail('fake@thibaud-bardin.com');
        $userAdmin->addRole('ROLE_ADMIN');
        $userAdmin->addRole('ROLE_ALLOWED_TO_SWITCH');
        $userAdmin->setEnabled(true);
        $manager->persist($userAdmin);

        $user = new User();
        $user->setUsername('user');
        $user->setPlainPassword('user');
        $user->setEmail('fak@thibaud-bardin.com');
        $user->addRole('ROLE_USER');
        $user->setEnabled(true);
        $manager->persist($user);

        $manager->flush();
    }
}