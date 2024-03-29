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
use Elektra\SeedBundle\Entity\Companies\PartnerTier;

/**
 * Class PartnerTierFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class PartnerTierFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {
        $statuses = array(
            array("Tier 1", 5),
            array("Tier 2", 1)
        );

        foreach ($statuses as $data) {
            $status = new PartnerTier();
            $status->setName($data[0]);
            $status->setUnitsLimit($data[1]);
            $manager->persist($status);

            $this->addReference('partner_tier-' . strtolower($status->getName()), $status);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 19;
    }
}