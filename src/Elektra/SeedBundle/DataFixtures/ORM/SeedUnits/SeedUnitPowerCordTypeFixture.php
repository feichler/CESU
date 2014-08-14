<?php

namespace Elektra\SeedBundle\DataFixtures\ORM\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType;

class SeedUnitPowerCordTypeFixture extends SeedBundleFixture
{

    protected function doLoad(ObjectManager $manager)
    {

        $typeA = new SeedUnitPowerCordType();
        $typeA->setName('Power Cord Type A');
        $typeA->setDescription('Description for "Power Cord Type A"');
        $manager->persist($typeA);

        $typeB = new SeedUnitPowerCordType();
        $typeB->setName('Power Cord Type B');
        $typeB->setDescription('Description for "Power Cord Type B"');
        $manager->persist($typeB);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 1003;
    }
}