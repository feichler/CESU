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
use Elektra\SeedBundle\Entity\Events\UnitStatus;

/**
 * Class UnitStatusFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class UnitStatusFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $statuses = array(
            array("Available", "A", "available"),
            array("Reserved", "R", "reserved"),
            array("Exception", "E", "exception"),
            array("In transit", "I", "inTransit"),
            array("Delivered", "D", "delivered"),
            array("Ackn. Attempt", "AA", "acknowledgeAttempt"),
            array("AA1 email sent", "AA1", "aa1sent"),
            array("AA3 email sent", "AA2", "aa3sent"),
            array("AA2 email sent", "AA3", "aa2sent"),
            array("Escalation", "Es", "escalation"),
            array("Delivery Verified", "DV", "deliveryVerified"),
            array("Unit installed at partner", "PIP", "unitInstalledAtPartner"),
            array("Unit installed at customer", "PIC", "unitInstalledAtCustomer")
        );

        foreach ($statuses as $data) {
            $status = new UnitStatus();
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