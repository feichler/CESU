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
use Elektra\SeedBundle\Entity\Companies\Address;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;

/**
 * Class WarehouseLocationFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class WarehouseLocationFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $names = array(
            array("Warehouse US", "FL-WH-US1", "country-two-us", "NY", "New York", "1234", "Street info 1"),
            array("Warehouse EU", "FL-WH-EU1", "country-two-at", "Vienna", "Vienna", "1234", "Street info 1"),
        );

        foreach ($names as $data) {
            $address = new Address();
            $address->setCountry($this->getReference($data[2]));
            $address->setState($data[3]);
            $address->setCity($data[4]);
            $address->setPostalCode($data[5]);
            $address->setStreet1($data[6]);

            $location = new WarehouseLocation();
            $location->setShortName($data[0]);
            $location->setLocationIdentifier($data[1]);
            $location->setAddress($address);

            $manager->persist($location);

            $this->addReference('warehouse_location-' . strtolower($location->getLocationIdentifier()), $location);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 18;
    }
}