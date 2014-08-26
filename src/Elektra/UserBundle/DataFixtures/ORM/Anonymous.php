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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Elektra\UserBundle\Entity\User;

/**
 * Class Anonymous
 *
 * @package Elektra\UserBundle\DataFixtures\ORM
 *
 * @version 0.1-dev
 */
class Anonymous extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        $anon = new User();
        $anon->setUsername('anonymous');
        $anon->setPlainPassword('anonymous');
        $anon->setFirstName('Anonymous');
        $anon->setLastName('User');
        $anon->setEmail('anonymous@elektra.aurealis.at');
        $anon->setEnabled(false);
        $manager->persist($anon);

        $manager->flush();

        $this->addReference('user-anonymous', $anon);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {

        return 2;
    }
}