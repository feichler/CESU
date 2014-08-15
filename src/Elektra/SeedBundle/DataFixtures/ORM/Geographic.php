<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\Region;

/**
 * Class Geographic
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM
 *
 * @version 0.1-dev
 */
class Geographic extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $regions = array();

        $csvPath = dirname(__DIR__) . '/Data/countries.csv';
        $csvFile = fopen($csvPath, 'r+');

        while (($row = fgetcsv($csvFile, 1000, ';', '"'))) {
            /*
             * 0 => name
             * 1 => alpha two
             * 2 => alpha three
             * 3 => numeric
             * 4 => region
             */
            if (!array_key_exists($row[4], $regions)) {
                $regions[$row[4]] = $this->createRegion($manager, $row[4]);
            }
            $this->createCountry($manager, $regions, $row);
        }

        fclose($csvFile);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {

        return 1001;
    }

    /**
     * @param ObjectManager $manager
     * @param string        $name
     *
     * @return Region
     */
    protected function createRegion(ObjectManager $manager, $name)
    {

        $region = new Region();
        $region->setName($name);

        $manager->persist($region);

        return $region;
    }

    /**
     * @param ObjectManager $manager
     * @param array         $regions
     * @param array         $params
     */
    protected function createCountry(ObjectManager $manager, $regions, $params)
    {

        $country = new Country();
        $country->setName(utf8_encode($params[0]));
        $country->setAlphaTwo(utf8_encode($params[1]));
        $country->setAlphaThree(utf8_encode($params[2]));
        $country->setNumericCode(utf8_encode($params[3]));
        $country->setRegion($regions[$params[4]]);

        $manager->persist($country);
    }
}