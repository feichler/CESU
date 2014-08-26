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
//use Doctrine\Common\Persistence\ObjectManager;
//use Elektra\SeedBundle\DataFixtures\SeedBundleFixture;
//use Elektra\SeedBundle\Entity\Companies\Country;
//use Elektra\SeedBundle\Entity\Companies\Partner;
//use Elektra\SeedBundle\Entity\Companies\Region;
//
///**
// * Class Geographic
// *
// * @package Elektra\SeedBundle\DataFixtures\ORM
// *
// * @version 0.1-dev
// */
//class Temporary extends SeedBundleFixture
//{
//
//    /**
//     * @param ObjectManager $manager
//     */
//    protected function doLoad(ObjectManager $manager)
//    {
//
//        $this->loadPartners($manager);
//    }
//
//    protected function loadPartners(ObjectManager $manager)
//    {
//
//        $partner = new Partner();
//        $partner->setName('SIRIUS COMPUTER SOLUTIONS INC.');
//        $partner->setShortName('SIRIUS');
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getOrder()
//    {
//
//        return 10000;
//    }
//}