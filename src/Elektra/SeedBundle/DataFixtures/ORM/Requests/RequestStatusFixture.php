<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\DataFixtures\ORM\Requests;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Requests\RequestStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

/**
 * Class RequestStatusFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Requests
 *
 * @version 0.1-dev
 */
class RequestStatusFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $statuses = array(
            array("Create - TOC"),
            array("Create - Objectives"),
            array("Created"),
        );

        foreach ($statuses as $data) {
            $status = new RequestStatus();
            $status->setName($data[0]);
            $manager->persist($status);

            $this->addReference('reqeust_status-' . strtolower($status->getName()), $status);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 20;
    }
}