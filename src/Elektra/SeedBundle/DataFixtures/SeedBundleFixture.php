<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SeedBundleFixture
 *
 * @package Elektra\SeedBundle\DataFixtures
 *
 * @version 0.1-dev
 */
abstract class SeedBundleFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public final function load(ObjectManager $manager)
    {

        $this->loadReferences();
        $this->doLoad($manager);
    }

    /**
     *
     */
    protected function loadReferences()
    {

        $adminUser = $this->getReference('user-administrator');

        $adminUserToken = new UsernamePasswordToken($adminUser->getUsername(), null, 'main', $adminUser->getRoles());
        $adminUserToken->setUser($adminUser);
        $this->container->get('security.context')->setToken($adminUserToken);
    }

    /**
     * @param ObjectManager $manager
     */
    protected abstract function doLoad(ObjectManager $manager);
}