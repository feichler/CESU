<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\DataFixtures\ORM\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType;

/**
 * Class SeedUnitPowerCordTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 *          @version 0.1-dev
 */
class SeedUnitPowerCordTypeFixture extends SeedBundleFixture
{
    /**
     * {@inheritdoc}
     */
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