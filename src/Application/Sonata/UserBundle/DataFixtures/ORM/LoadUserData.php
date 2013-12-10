<?php

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEmail('admin@yourbooks.fr');
        $userAdmin->addGroup($this->getReference('group-super-admin'));
        $userAdmin->setEnabled(true);
        $manager->persist($userAdmin);

        $userAuthor = new User();
        $userAuthor->setUsername('author');
        $userAuthor->setPlainPassword('author');
        $userAuthor->setEmail('author@yourbooks.fr');
        $userAuthor->setEnabled(true);
        $userAuthor->addGroup($this->getReference('group-author'));
        $manager->persist($userAuthor);

        $userReader = new User();
        $userReader->setUsername('reader');
        $userReader->setPlainPassword('reader');
        $userReader->setEmail('reader@yourbooks.fr');
        $userReader->setEnabled(true);
        $userReader->addGroup($this->getReference('group-reader'));
        $manager->persist($userReader);

        $userEditor = new User();
        $userEditor->setUsername('editor');
        $userEditor->setPlainPassword('editor');
        $userEditor->setEmail('editor@yourbooks.fr');
        $userEditor->setEnabled(true);
        $userEditor->addGroup($this->getReference('group-editor'));
        $manager->persist($userEditor);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}