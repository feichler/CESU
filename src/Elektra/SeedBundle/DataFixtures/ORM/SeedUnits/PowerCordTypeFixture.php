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
use Elektra\SeedBundle\Entity\SeedUnits\PowerCordType;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType;

/**
 * Class SeedUnitPowerCordTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class PowerCordTypeFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $entries = array(
            'A',
            'B',
            'C',
            'D',
        );

        foreach ($entries as $entry) {
            $obj = new PowerCordType();
            $obj->setName('Power Type ' . $entry);
            $obj->setDescription('Description ' . $entry);

            $manager->persist($obj);

            $this->addReference('power_type-' . strtolower($entry), $obj);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 13;
    }
}