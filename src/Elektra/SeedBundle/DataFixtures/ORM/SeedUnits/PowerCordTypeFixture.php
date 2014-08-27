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
            'Argentina',
            'Australia',
            'Brazil',
            'China',
            'India',
            'Japan',
            'North America',
        );

        foreach ($entries as $entry) {
            $obj = new PowerCordType();
            $obj->setName($entry);
            $obj->setDescription('Description for Power Cord Type "' . $entry . '"');

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