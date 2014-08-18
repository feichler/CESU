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
use Elektra\SeedBundle\Entity\Companies\AddressType;

/**
 * Class AddressTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 *          @version 0.1-dev
 */
class AddressTypeFixture extends SeedBundleFixture
{
    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {
        $statuses = array(
            "Billing",
            "Shipping"
        );

        foreach($statuses as $data)
        {
            $status = new AddressType();
            $status->setName($data);
            $manager->persist($status);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {
        return 1002;
    }
}