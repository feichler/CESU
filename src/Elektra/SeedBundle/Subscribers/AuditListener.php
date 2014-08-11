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
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\IAuditContainer;
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

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!($entity instanceof IAuditContainer)) {

            return;
        }

        $audit = new Audit();
        $audit->setCreatedAt($this->getTimestamp());
        $audit->setCreatedBy($this->getUser());
        $entity->setAudit($audit);

        // TODO src: the following lines produce an error (not managed entity)
        //        $mgr = $args->getEntityManager();
        //        $uow = $mgr->getUnitOfWork();
        //        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($entity)), $entity);
        //        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($audit)), $audit);
    }

    // TODO src: preUpdate cannot update referenced entries
    // TODO src: see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#preupdate
        public function preUpdate(PreUpdateEventArgs $args)
        {
    //
    //        $entity = $args->getEntity();
    //
    //        if (!($entity instanceof IAuditContainer)) {
    //
    //            return;
    //        }
    //
    //        $audit = $entity->getAudit();
    //        $this->container->get('session')->getFlashBag()->add('warning', 'ASDF: ' . $audit->getId());
    //        $audit->setModifiedAt($this->getTimestamp());
    //        $audit->setModifiedBy($this->getUser());
    //        //        $entity->setAudit($audit);
    //        $this->container->get('session')->getFlashBag()->add('warning', 'ASDF: ' . $entity->getAudit()->getModifiedAt());
    //        //$args->setNewValue('audit',$audit);
    //        $em = $args->getEntityManager();
    //
    //        //        $em->flush($audit);
    //        $uow = $em->getUnitOfWork();
    //        $uow->computeChangeSets();
    //        $test = $uow->isEntityScheduled($audit);
    //        $uow->scheduleForUpdate($audit);
    //
    //        if ($test) {
    //            $this->container->get('session')->getFlashBag()->add('warning', 'scheduled');
    //        } else {
    //            $this->container->get('session')->getFlashBag()->add('warning', 'NOT scheduled');
    //        }
    //        $md = $em->getClassMetadata(get_class($audit));
    //        $uow->computeChangeSet($md, $audit);
    //        //        $uow->computeChangeSet($em->getClassMetadata($entity), $entity);
    //        $uow->recomputeSingleEntityChangeSet($md, $audit);
    //
    //        //var_dump();
    //        //        $mgr = $args->getEntityManager();
    //        //        $uow = $mgr->getUnitOfWork();
    //        //        $uow->recomputeSingleEntityChangeSet($mgr->getClassMetadata(get_class($audit)), $audit);
        }

    public function onFlush(OnFlushEventArgs $args)
    {

        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $updated) {
            if ($updated instanceof IAuditContainer) {
                $audit = $updated->getAudit();
                $audit->setModifiedAt($this->getTimestamp());
                $audit->setModifiedBy($this->getUser());
            }
        }

        $uow->computeChangeSets();
    }

    protected function getTimestamp()
    {

        return time();
    }

    protected function getUser()
    {

        return $this->container->get('security.context')->getToken()->getUser();
    }
}