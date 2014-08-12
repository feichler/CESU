<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 11:54
 */

namespace Elektra\SeedBundle\Subscribers;


use Doctrine\Common\EventSubscriber;
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
    {echo 'ASF';
        $entity = $args->getEntity();

        if (!($entity instanceof AuditableInterface))
            return;

        $audit = new Audit();
        $audit->setCreatedAt($this->getTimestamp());
        $audit->setCreatedBy($this->getUser());

        $entity->setAudit($audit);

        $mgr = $args->getEntityManager();
        $uow = $mgr->getUnitOfWork();
        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($entity)), $entity);
        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($audit)), $audit);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof AuditableInterface))
            return;

        $audit = $entity->getAudit();
        $audit->setModifiedAt($this->getTimestamp());
        $audit->setModifiedBy($this->getUser());

        $mgr = $args->getEntityManager();
        $uow = $mgr->getUnitOfWork();
        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($audit)), $audit);
    }

    private function getTimestamp()
    {
        return time();
    }

    private function getUser()
    {
        return null;
    }
} 