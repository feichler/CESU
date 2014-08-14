<?php

namespace Elektra\SeedBundle\DataFixtures\ORM\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

class SeedUnitModelFixture extends SeedBundleFixture
{

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

        return 1002; // second in seed bundle
    }
}