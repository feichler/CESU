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
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;

/**
 * Class ShippingStatusFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class ShippingStatusFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $statuses = array(
            array("Available", "A", ShippingStatus::AVAILABLE),
            array("Reserved", "R", ShippingStatus::RESERVED),
            array("Exception", "E", ShippingStatus::EXCEPTION),
            array("Shipped", "S", ShippingStatus::SHIPPED),
            array("In transit", "I", ShippingStatus::IN_TRANSIT),
            array("Delivered", "D", ShippingStatus::DELIVERED),
            array("Ackn. Attempt", "AA", ShippingStatus::ACKNOWLEDGE_ATTEMPT),
            array("AA1 email sent", "AA1", ShippingStatus::AA1SENT),
            array("AA2 email sent", "AA2", ShippingStatus::AA2SENT),
            array("AA3 email sent", "AA3", ShippingStatus::AA3SENT),
            array("Escalation", "Es", ShippingStatus::ESCALATION),
            array("Delivery Verified", "DV", ShippingStatus::DELIVERY_VERIFIED),
        );

        foreach ($statuses as $data) {
            $status = new ShippingStatus();
            $status->setName($data[0]);
            $status->setAbbreviation($data[1]);
            $status->setInternalName($data[2]);
            $manager->persist($status);

            $this->addReference('status-' . $status->getInternalName(), $status);
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