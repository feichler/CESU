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
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AuditListener
 *
 * @package Elektra\SeedBundle\Subscribers
 *
 *          @version 0.1-dev
 */
class AuditListener
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

        foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $updated)
        {
            if ($updated instanceof AuditableInterface)
            {
                $audit = new Audit();
                $audit->setTimestamp(time());
                $audit->setUser($this->getUser());
                $updated->getAudits()->add($audit);
            }
        }

        $uow->computeChangeSets();
    }

    /**
     * @return User
     */
    private function getUser()
    {
        $token = $this->container->get('security.context')->getToken();
        return $token != null ? $token->getUser() : null;
    }
}