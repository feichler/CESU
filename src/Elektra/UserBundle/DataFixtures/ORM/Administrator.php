<?php

namespace Elektra\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Elektra\UserBundle\Entity\User;

class Administrator implements FixtureInterface
{

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {

        $admin = new User();
        $admin->setUsername('administrator');
        $admin->setPlainPassword('administrator');
//        $admin->setPassword('administrator');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setEmail('administrator@elektra.aurealis.at');
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);
        $manager->persist($admin);

        $manager->flush();
    }
}