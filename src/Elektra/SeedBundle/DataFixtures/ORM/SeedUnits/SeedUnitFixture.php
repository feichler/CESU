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

        $entries = array(
            'argentina'     => array(
                'SFTX182810D8',
                'SFTX182810CZ',
                'SFTX182810D0',
                'SFTX182810D3',
                'SFTX182810D4',
                'SFTX182810D7',
                'SFTX182810D2',
                'SFTX182810D1',
                'SFTX182810D6',
                'SFTX182810D5',
            ),
            'australia'     => array(
                'SFTX182810CW',
                'SFTX182810CQ',
                'SFTX182810CS',
                'SFTX182810CR',
                'SFTX182810CY',
                'SFTX182810CV',
                'SFTX182810CU',
                'SFTX182810CX',
                'SFTX182810CT',
                'SFTX182810CP',
            ),
            'brazil'        => array(
                'SFTX182810E1',
                'SFTX182810E5',
                'SFTX182810E6',
                'SFTX182810G7',
                'SFTX182810J8',
                'SFTX182810G8',
                'SFTX182810E0',
                'SFTX182810DV',
                'SFTX182810DX',
                'SFTX182810GA',
                'SFTX182810ED',
                'SFTX182810DZ',
                'SFTX182810J7',
                'SFTX182810DY',
                'SFTX182810E9',
                'SFTX182810GB',
                'SFTX182810E3',
                'SFTX182810G9',
                'SFTX182810DW',
                'SFTX182810EF',
                'SFTX182810EC',
                'SFTX182810E4',
                'SFTX182810E2',
                'SFTX182810J6',
                'SFTX182810EE',
            ),
            'china'         => array(
                'SFTX182810AP',
                'SFTX182810AN',
                'SFTX182810AK',
                'SFTX182810AR',
                'SFTX182810AH',
                'SFTX182810AQ',
                'SFTX182810AL',
                'SFTX182810AJ',
                'SFTX182810AM',
                'SFTX182810AG',
            ),
            'india'         => array(
                'SFTX182810DU',
                'SFTX182810DN',
                'SFTX182810DS',
                'SFTX182810DF',
                'SFTX182810DC',
                'SFTX182810DL',
                'SFTX182810DB',
                'SFTX182810DR',
                'SFTX182810DE',
                'SFTX182810D9',
                'SFTX182810DA',
                'SFTX182810DQ',
                'SFTX182810DD',
                'SFTX182810DP',
                'SFTX182810DJ',
                'SFTX182810DG',
                'SFTX182810DK',
                'SFTX182810DH',
                'SFTX182810DM',
                'SFTX182810DT',
            ),
            'japan'         => array(
                'SFTX182810K7',
                'SFTX182810JT',
                'SFTX182810JR',
                'SFTX182810JC',
                'SFTX182810KA',
                'SFTX182810JS',
                'SFTX182810KC',
                'SFTX182810K8',
                'SFTX182810K9',
                'SFTX182810KD',
                'SFTX182810K5',
                'SFTX182810KB',
                'SFTX182810K6',
                'SFTX182810K2',
                'SFTX182810KF',
                'SFTX182810JG',
                'SFTX182810JM',
                'SFTX182810JF',
                'SFTX182810J9',
                'SFTX182810KG',
                'SFTX182810K3',
                'SFTX182810JA',
                'SFTX182810KL',
                'SFTX182810KN',
                'SFTX182810KK',
                'SFTX182810JL',
                'SFTX182810KE',
                'SFTX182810JP',
                'SFTX182810JH',
                'SFTX182810JQ',
                'SFTX182810JV',
                'SFTX182810JU',
                'SFTX182810KH',
                'SFTX182810KJ',
                'SFTX182810JJ',
                'SFTX182810JB',
                'SFTX182810JK',
                'SFTX182810KM',
                'SFTX182810K4',
                'SFTX182810JN',
            ),
            'north america' => array(
                'SFTX18301068',
                'SFTX1830103B',
                'SFTX18301011',
                'SFTX18301012',
                'SFTX1830106C',
                'SFTX1830106K',
                'SFTX1830100Z',
                'SFTX1830103A',
                'SFTX18301065',
                'SFTX1830106B',
                'SFTX1830106F',
                'SFTX1830106J',
            ),
        );

        $model = 'Cisco ASA 5515';

        foreach($entries as $pc => $serials) {
            foreach($serials as $serial) {
          $obj = new SeedUnit();
                $obj->setSerialNumber($serial);
                $obj->setModel($this->getReference('model-'.strtolower($model)));
                $obj->setPowerCordType($this->getReference('power_type-'.$pc));

                $manager->persist($obj);

                $this->addReference('seed_unit-' . strtolower($serial), $obj);
            }
        }

//        $entries = array();
//        $models  = array('Cisco ASA 5515');
//        $powers  = array('A', 'B', 'C', 'D');
//        for ($i = 1; $i <= 150; $i++) {
//            $serial           = str_pad($i, 3, '0', STR_PAD_LEFT);
//            $model            = $models[array_rand($models)];
//            $power            = $powers[array_rand($powers)];
//            $entries[$serial] = array($model, $power);
//        }
//
//        foreach ($entries as $key => $entry) {
//            $obj = new SeedUnit();
//            $obj->setSerialNumber($key);
//            $obj->setModel($this->getReference('model-' . strtolower($entry[0])));
//            $obj->setPowerCordType($this->getReference('power_type-' . strtolower($entry[1])));
//
//            $manager->persist($obj);
//
//            $this->addReference('seed_unit-' . strtolower($key), $obj);
//        }

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
