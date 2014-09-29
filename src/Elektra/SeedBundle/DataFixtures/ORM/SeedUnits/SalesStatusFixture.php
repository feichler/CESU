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
use Elektra\SeedBundle\Entity\SeedUnits\SalesStatus;

/**
 * Class SalesStatusFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class SalesStatusFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $data = array(
            array("Test Sales 1", "TS1"),
            array("Test Sales 2", "TS2")
        );

        foreach ($data as $entry) {
            $usage = new SalesStatus();
            $usage->setName($entry[0]);
            $usage->setAbbreviation($entry[1]);
            $manager->persist($usage);

            $this->addReference('salesStatus-' . $usage->getAbbreviation(), $usage);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 15;
    }
}