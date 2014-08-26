<?php
///**
// * @author    Florian Eichler <florian@eichler.co.at>
// * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
// * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
// * @license   MINOR add a license
// * @version   0.1-dev
// */
//
//namespace Elektra\SeedBundle\DataFixtures\ORM;
//
//use Doctrine\Common\DataFixtures\AbstractFixture;
//use Doctrine\Common\DataFixtures\Doctrine;
//use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
//use Elektra\SeedBundle\Entity\SeedUnits\Model;
//use Elektra\SeedBundle\Entity\SeedUnits\PowerCordType;
//use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
//
///**
// * Class Administrator
// *
// * @package Elektra\SeedBundle\DataFixtures\ORM
// *
// * @version 0.1-dev
// */
//class Testing extends AbstractFixture implements OrderedFixtureInterface
//{
//
//    /**
//     * {@inheritdoc}
//     */
//    public function load(ObjectManager $manager)
//    {
//
//        $models = array('A', 'B', 'C');
//        $powers = array('A','B','C');
//        $units =  array('A','B','C');
//
//        foreach ($models as $id) {
//            $model = new Model();
//            $model->setName('Model "' . $id . '"');
//            $model->setDescription('Description Model "' . $id . '"');
//            $manager->persist($model);
//            $this->setReference('model-'.$id,$model);
//        }
//
//        foreach ($powers as $id) {
//            $power = new PowerCordType();
//            $power->setName('Power "' . $id . '"');
//            $power->setDescription('Description Power "' . $id . '"');
//            $manager->persist($power);
//            $this->setReference('power-'.$id,$power);
//        }
//
//        foreach($units as $id) {
//            $unit = new SeedUnit();
//            $unit->setSerialNumber($id);
//            $unit->setModel($this->getReference('model-'.$id));
//            $unit->setPowerCordType($this->getReference('power-'.$id));
//            $manager->persist($unit);
//            $this->setReference('unit-'.$id,$unit);
//        }
//
//        $manager->flush();
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getOrder()
//    {
//
//        return 11;
//    }
//}