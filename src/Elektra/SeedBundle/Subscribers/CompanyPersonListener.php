<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Subscribers;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CompanyPersonListener
 *
 * @package Elektra\SeedBundle\Subscribers
 *
 * @version 0.1-dev
 */
class CompanyPersonListener
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

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {

        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $changed = false;
        foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $entity)
        {
            if ($entity instanceof CompanyPerson)
            {
                $changed |= $this->ensureOnlyOnePrimary($entity);
            }
        }

        if ($changed)
        {
            $uow->computeChangeSets();
        }
    }

    private function ensureOnlyOnePrimary(CompanyPerson $person)
    {
        $changed = false;
        if ($person->getIsPrimary())
        {
            foreach ($person->getLocation()->getCompany()->getLocations() as $location)
            {
                /** @var $location CompanyLocation */

                foreach($location->getPersons() as $otherPerson)
                {
                    /** @var $otherPerson CompanyPerson */

                    if ($otherPerson !== $person && $otherPerson->getIsPrimary())
                    {
                        $otherPerson->setIsPrimary(false);
                        $changed = true;
                    }
                }
            }
        }

        return $changed;
    }
}