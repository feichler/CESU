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
use Elektra\SeedBundle\Entity\SeedUnits\Model;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

/**
 * Class SeedUnitModelFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class SeedUnitFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $entries = array();
        $models  = array('A', 'B', 'C', 'D');
        $powers  = array('A', 'B', 'C', 'D');
        for ($i = 1; $i <= 150; $i++) {
            $serial           = str_pad($i, 3, '0', STR_PAD_LEFT);
            $model            = $models[array_rand($models)];
            $power            = $powers[array_rand($powers)];
            $entries[$serial] = array($model, $power);
        }

        foreach ($entries as $key => $entry) {
            $obj = new SeedUnit();
            $obj->setSerialNumber($key);
            $obj->setModel($this->getReference('model-' . strtolower($entry[0])));
            $obj->setPowerCordType($this->getReference('power_type-' . strtolower($entry[1])));

            $manager->persist($obj);

            $this->addReference('seed_unit-' . strtolower($key), $obj);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    function getOrder()
    {

        return 14;
    }
}
