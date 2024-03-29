<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {

        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Common third-party bundles
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
//            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            // Specific Elektra Bundles
            new Elektra\UserBundle\ElektraUserBundle(),
            new Elektra\SiteBundle\ElektraSiteBundle(),
            new Elektra\CrudBundle\ElektraCrudBundle(),
            new Elektra\SeedBundle\ElektraSeedBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {

        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
