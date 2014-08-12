<?php
namespace Elektra\SeedBundle\DataFixtures\ORM\SeedUnits;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\DataFixtures\AbstractFixture;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\Region;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\UserBundle\Entity\User;

class SeedUnitModels extends AbstractFixture
{

    /**
     * {@inheritdoc}
     */
    public function doLoad(ObjectManager $manager)
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

    public function getOrder()
    {

        return 1002;
    }
}