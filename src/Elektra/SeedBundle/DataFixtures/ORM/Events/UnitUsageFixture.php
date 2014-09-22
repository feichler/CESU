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
            array("Idle", "I", 'idle'),
            array("Utilized for training", "T", time() . rand()),
            array("Installed in partner lab", "P", time() . rand()),
            array("Installed at customer location", "C", time() . rand()),
        );

        foreach ($data as $entry) {
            $usage = new UnitUsage();
            $usage->setName($entry[0]);
            $usage->setAbbreviation($entry[1]);
            $usage->setInternalName($entry[2]);
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