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
use Elektra\SeedBundle\Entity\Companies\GenericLocation;

/**
 * Class GenericLocationFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class GenericLocationFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $data = array(
            array("In transit", GenericLocation::IN_TRANSIT),
            array("n/a", GenericLocation::UNKNOWN)
        );

        foreach ($data as $entry) {
            $location = new GenericLocation();
            $location->setShortName($entry[0]);
            $location->setInternalName($entry[1]);
            $manager->persist($location);

            $this->addReference('generic_location-' . strtolower($location->getInternalName()), $location);
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