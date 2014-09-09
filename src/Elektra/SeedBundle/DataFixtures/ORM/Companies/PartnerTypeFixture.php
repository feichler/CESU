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
use Elektra\SeedBundle\Entity\Companies\PartnerType;

/**
 * Class PartnerTierFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class PartnerTypeFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {
        $data = array(
            array("Partner Organisation"),
            array("Sales Team")
        );

        foreach ($data as $entry) {
            $obj = new PartnerType();
            $obj->setName($entry[0]);
            $manager->persist($obj);

            $this->addReference('partner_type-' . strtolower($obj->getName()), $obj);
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