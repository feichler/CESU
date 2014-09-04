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
use Elektra\SeedBundle\Entity\Companies\ContactInfoType;

/**
 * Class ContactInfoTypeFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\Companies
 *
 * @version 0.1-dev
 */
class ContactInfoTypeFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $statuses = array(
            array("Email", ContactInfoType::EMAIL),
            array("Phone", ContactInfoType::PHONE),
            array("Fax", ContactInfoType::FAX),
            array("Mobile", ContactInfoType::MOBILE),
            array("Messenger", ContactInfoType::MESSENGER),
            array("Other", ContactInfoType::OTHER),
        );

        foreach ($statuses as $data) {
            $status = new ContactInfoType();
            $status->setName($data[0]);
            $status->setInternalName($data[1]);
            $manager->persist($status);

            $this->addReference('contact_info_type-' . strtolower($status->getName()), $status);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 17;
    }
}