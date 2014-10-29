<?php

namespace Elektra\SeedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SiteBundle\Entity\AbstractEntity;

abstract class AbstractAuditableEntity extends AbstractEntity implements AuditableEntityInterface
{

    /**
     * @var Collection Audit[]
     */
    protected $audits;

    /**
     * Constructor
     *
     * @inheritdoc
     */
    public function __construct()
    {

        parent::__construct();

        $this->audits = new ArrayCollection();
    }

    /*************************************************************************
     * AuditableInterface
     *************************************************************************/

    /**
     * @inheritdoc
     */
    public function getAudits()
    {

        return $this->audits;
    }

    /**
     * @inheritdoc
     */
    public function setAudits($audits)
    {

        $this->audits = $audits;
    }

    /**
     * @inheritdoc
     */
    public function addAudit(Audit $audit)
    {

        $this->getAudits()->add($audit);
    }

    /*************************************************************************
     * Required entity methods
     *************************************************************************/

    /**
     * @inheritdoc
     */
    public function getFirstAudit()
    {

        /** @var Audit $firstAudit */
        $firstAudit = null;

        foreach ($this->audits as $audit) {
            if ($audit instanceof Audit) {
                if ($firstAudit === null) {
                    $firstAudit = $audit;
                } else {
                    if ($firstAudit->getTimestamp() > $audit->getTimestamp()) {
                        $firstAudit = $audit;
                    }
                }
            }
        }

        return $firstAudit;
    }

    /**
     * @inheritdoc
     */
    public function getLastAudit()
    {

        /** @var Audit $lastAudit */
        $lastAudit = null;

        foreach ($this->audits as $audit) {
            if ($audit instanceof Audit) {
                if ($lastAudit === null) {
                    $lastAudit = $audit;
                } else {
                    if ($lastAudit->getTimestamp() < $audit->getTimestamp()) {
                        $lastAudit = $audit;
                    }
                }
            }
        }

        return $lastAudit;
    }

    /**
     * @inheritdoc
     */
    public function getCreationAudit()
    {

        return $this->getFirstAudit();
    }

    /**
     * @inheritdoc
     */
    public function getLastModifiedAudit()
    {

        if (count($this->audits) > 1) {
            // only modified if more than 1 audit exists -> first audit is the creation
            return $this->getLastAudit();
        }

        return null;
    }
}