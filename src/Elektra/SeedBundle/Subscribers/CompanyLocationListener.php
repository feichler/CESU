<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Subscribers;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CompanyLocationListener
 *
 * @package Elektra\SeedBundle\Subscribers
 *
 * @version 0.1-dev
 */
class CompanyLocationListener
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof CompanyLocation)
        {
            $this->ensureOnlyOnePrimary($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof CompanyLocation)
        {
            $this->ensureOnlyOnePrimary($entity);
        }
    }

    private function ensureOnlyOnePrimary(CompanyLocation $location)
    {
        $changed = false;
        if ($location instanceof CompanyLocation && $location->getIsPrimary())
        {
            foreach ($location->getCompany()->getLocations() as $otherLocation)
            {
                /** @var $otherLocation CompanyLocation */
                if ($otherLocation !== $location && $otherLocation->getIsPrimary())
                {
                    $otherLocation->setIsPrimary(false);
                    $changed = true;
                }
            }
        }

        return $changed;
    }
}