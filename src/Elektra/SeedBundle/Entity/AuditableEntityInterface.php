<?php

namespace Elektra\SeedBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SiteBundle\Entity\EntityInterface;

interface AuditableEntityInterface extends EntityInterface
{

    /**
     * Get the list of audits on this entity
     *
     * @return Collection Audit[]
     */
    public function getAudits();

    /**
     * Set the list of audits on this entity
     *
     * @param Collection Audit[] $audits
     *
     * @return void
     */
    public function setAudits($audits);

    /**
     * Add one audit to the audits on this entity
     *
     * @param Audit $audit
     *
     * @return void
     */
    public function addAudit(Audit $audit);

    /**
     * Get the first audit of this entity (by timestamp DESC)
     *
     * @return Audit|null
     */
    public function getFirstAudit();

    /**
     * Get the last audit of this entity (by timestamp DESC)
     *
     * @return Audit|null
     */
    public function getLastAudit();

    /**
     * Get the first stored audit of this entity
     *
     * @return Audit|null
     */
    public function getCreationAudit();

    /**
     * Get the last stored audit of this entity if not the only audit
     *
     * @return Audit|null
     */
    public function getLastModifiedAudit();
}