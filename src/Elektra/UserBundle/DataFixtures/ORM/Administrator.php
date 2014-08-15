<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Elektra\UserBundle\Entity\User;

/**
 * Class Administrator
 *
 * @package Elektra\UserBundle\DataFixtures\ORM
 *
 * @version 0.1-dev
 */
class Administrator extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritdoc}
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

        $this->addReference('admin-user', $admin);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {

        return 1;
    }
}