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
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\UnitStatus;

/**
 * Class EventTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class EventTypeFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $statuses = array(
            array("Shipping", "shipping"),
            array("Partner", "partner"),
            array("Sales", "sales"),
            array("Communication", "communication"),
        );

        foreach ($statuses as $data) {
            $eventType = new EventType();
            $eventType->setName($data[0]);
            $eventType->setInternalName($data[1]);
            $manager->persist($eventType);

            $this->addReference('eventType-' . $eventType->getInternalName(), $eventType);
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