<?php


namespace Elektra\SeedBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    protected function loadReferences()
    {

        $adminUser = $this->getReference('admin-user');

        $adminUserToken = new UsernamePasswordToken($adminUser->getUsername(), null, 'main', $adminUser->getRoles());
        $adminUserToken->setUser($adminUser);
        $this->container->get('security.context')->setToken($adminUserToken);
    }

    protected abstract function doLoad(ObjectManager $manager);
}