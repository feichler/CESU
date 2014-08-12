<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 11:54
 */

namespace Elektra\SeedBundle\Subscribers;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

class AuditSubscriber implements EventSubscriber
{
    public function __construct($securityContext) {

        echo get_class($securityContext)."<br />";
        exit();
    }


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::prePersist, Events::preUpdate);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof AuditableInterface))
            return;

        $this->addAudit($entity, $args->getEntityManager());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof AuditableInterface))
            return;

        $this->addAudit($entity, $args->getEntityManager());
    }

    /**
     * @param AuditableInterface $auditable
     * @param EntityManager $mgr
     */
    private function addAudit($auditable, $mgr)
    {
        $audit = new Audit();
        $audit->setTimestamp(time());
        // TODO: retrieve current user
        $audit->setUser(null);

        $auditable->getAudits()->add($audit);

        $uow = $mgr->getUnitOfWork();
        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($auditable)), $auditable);
        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($audit)), $audit);
    }
}