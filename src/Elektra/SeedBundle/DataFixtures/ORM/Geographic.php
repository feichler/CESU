<?php

namespace Elektra\SeedBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\Region;
use Elektra\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Geographic implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    function load(ObjectManager $manager)
    {
//        $em = $this->container->get('doctrine.orm.default_entity_manager');
//        //        $em->var_dump(get_class($em));
//        $this->getAdminUser();
//
//        return;
        $regions = array();

        $csvPath = dirname(__DIR__) . '/countries.csv';
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
                //                $manager->persist($regions[$row[4]]);
                //                $manager->flush();
            }
            $country = new Country();
            $country->setName(utf8_encode($row[0]));
            $country->setAlphaTwo(utf8_encode($row[1]));
            $country->setAlphaThree(utf8_encode($row[2]));
            $country->setNumericCode(utf8_encode($row[3]));
            $country->setRegion($regions[$row[4]]);
            //            $admin = $manager->find('ElektraUserBundle:User', 1);
            //            $audit = new Audit();
            //            $audit->setCreatedAt(time());
            //            $audit->setCreatedBy($admin);
            $country->getAudits()->add($this->getAudit($manager));

            $manager->persist($country);
            //            $manager->flush();
        }

        $manager->flush();
        fclose($csvFile);
    }

    protected function createRegion(ObjectManager $manager, $name)
    {
        $region = new Region();
        $region->setName($name);
        $region->getAudits()->add($this->getAudit($manager));
        $manager->persist($region);

        return $region;
    }

    protected function getAudit(ObjectManager $manager)
    {
//        static $admin = null;
//        if ($admin == null) {
//            $admin = $manager->find('ElektraUserBundle:User', 1);
//        }

        $audit = new Audit();
        $audit->setUser($this->getAdminUser());
        $audit->setTimestamp(time());

        return $audit;
    }

    protected function getAdminUser()
    {
        static $adminUser = null;

        if ($adminUser == null) {
            $em = $this->container->get('doctrine.orm.default_entity_manager');

            $qb = $em->createQueryBuilder();
            $qb->select('u');
            $qb->from('ElektraUserBundle:User', 'u');
            $qb->where('u.username = :username');
            $qb->setParameter('username', 'administrator');
            $query = $qb->getQuery();

            try {
                $adminUser = $query->getSingleResult();
            } catch (NoResultException $ex) {
                throw $ex;
            }
        }

        return $adminUser;
        //echo $user->getId();
        //        var_dump($user);
    }
}