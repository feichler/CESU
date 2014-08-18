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
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

/**
 * Class SeedUnitModelFixture
 *
 * @package Elektra\SeedBundle\DataFixtures\ORM\SeedUnits
 *
 * @version 0.1-dev
 */
class SeedUnitModelFixture extends SeedBundleFixture
{

    /**
     * {@inheritdoc}
     */
    protected function doLoad(ObjectManager $manager)
    {

        $modelA = new SeedUnitModel();
        $modelA->setName('Model A');
        $modelA->setDescription('Description for "Model A"');
        $manager->persist($modelA);

        $modelB = new SeedUnitModel();
        $modelB->setName('Model B');
        $modelB->setDescription('Description for "Model B"');
        $manager->persist($modelB);

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