<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\DataFixtures\ORM\Events;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\Events\UnitUsage;

/**
 * Class UnitStatusFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class UnitUsageFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $data = array(
            array("Idle", "I", 'idle', UnitUsage::LOCATION_SCOPE_PARTNER, UnitUsage::LOCATION_CONSTRAINT_OPTIONAL),
            array("Utilized for training", "T", null, UnitUsage::LOCATION_SCOPE_PARTNER, UnitUsage::LOCATION_CONSTRAINT_OPTIONAL),
            array("Installed in partner lab", "P", null, UnitUsage::LOCATION_SCOPE_PARTNER, UnitUsage::LOCATION_CONSTRAINT_OPTIONAL),
            array("Installed at customer location", "C", null, UnitUsage::LOCATION_SCOPE_CUSTOMER, UnitUsage::LOCATION_CONSTRAINT_REQUIRED),
        );

        foreach ($data as $entry) {
            $usage = new UnitUsage();
            $usage->setName($entry[0]);
            $usage->setAbbreviation($entry[1]);
            $usage->setInternalName($entry[2]);
            $usage->setLocationScope($entry[3]);
            $usage->setLocationConstraint($entry[4]);
            $manager->persist($usage);

            $this->addReference('usage-' . $usage->getAbbreviation(), $usage);
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