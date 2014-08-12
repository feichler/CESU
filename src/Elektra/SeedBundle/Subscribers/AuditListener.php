<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 11:54
 */

namespace Elektra\SeedBundle\Subscribers;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class AuditListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $updated)
        {
            if ($updated instanceof AuditableInterface)
            {
                $audit = new Audit();
                $audit->setTimestamp(time());
                $audit->setUser($this->container->get('security.context')->getToken()->getUser());
                $updated->getAudits()->add($audit);
            }
        }

        $uow->computeChangeSets();
    }
}