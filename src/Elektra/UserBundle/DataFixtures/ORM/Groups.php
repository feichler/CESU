<?php

namespace Elektra\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Groups extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        /*
         *
         */
    }

    public function getOrder()
    {

        return 2;
    }
}