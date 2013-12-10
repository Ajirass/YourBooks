<?php
/**
 * Created by Thibaud BARDIN (Irvyne)
 * This code is under the MIT License (https://github.com/Irvyne/license/blob/master/MIT.md)
 */

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $groupSuperAdmin = new Group('Super Administrateur');
        $groupSuperAdmin->addRole('ROLE_SUPER_ADMIN');
        $manager->persist($groupSuperAdmin);

        $groupAdmin = new Group('Administrateur');
        $groupAdmin->addRole('ROLE_ADMIN');
        $manager->persist($groupAdmin);

        $groupAuthor = new Group('Auteur');
        $groupAuthor->addRole('ROLE_AUTHOR');
        $manager->persist($groupAuthor);

        $groupReader = new Group('Lecteur');
        $groupReader->addRole('ROLE_READER');
        $manager->persist($groupReader);

        $groupEditor = new Group('Editeur');
        $groupEditor->addRole('ROLE_EDITOR');
        $manager->persist($groupEditor);

        $manager->flush();

        // Add References
        $this->addReference('group-super-admin',    $groupSuperAdmin);
        $this->addReference('group-admin',          $groupAdmin);
        $this->addReference('group-author',         $groupAuthor);
        $this->addReference('group-reader',         $groupReader);
        $this->addReference('group-editor',         $groupEditor);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}