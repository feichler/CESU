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
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\Partner;

/**
 * Class AddressTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class TempPartnerFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $partners = array(
            'PT1' => 'Partner 1',
            'PT2' => 'Partner 2',
            'PT3' => 'Partner 3',
            'PT4' => 'Partner 4',
            'PT5' => 'Partner 5',
        );

        foreach ($partners as $short => $long) {
            $obj = new Partner();
            $obj->setName($long);
            $obj->setShortName($short);
            $obj->setPartnerTier($this->getReference('partner_tier-tier 1'));
            $obj->setUnitsLimit(5);
            $manager->persist($obj);

//            $location = new CompanyLocation();
//            $location->setAddressType($this->getReference('address_type-billing'));
//            $location->setCompany($obj);
//            $location->setName('HQ');
//
        }

//        foreach ($statuses as $data) {
//            $status = new AddressType();
//            $status->setName($data);
//            $manager->persist($status);
//
//            $this->addReference('address_type-' . strtolower($status->getName()), $status);
//        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 100;
    }
}