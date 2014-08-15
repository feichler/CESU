<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;

/**
 * Class RequestStatus
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="requestStatuses")
 */
class RequestStatus implements AuditableInterface, CRUDEntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestStatusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "requestStatuses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "requestStatusId", referencedColumnName = "requestStatusId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->requestStatusId;
    }

    /**
     * @return int
     */
    public function getRequestStatusId()
    {
        return $this->requestStatusId;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setAudits($audits)
    {
        $this->audits = $audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getAudits()
    {
        return $this->audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationAudit()
    {
        return $this->getAudits()->slice(0, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        $audits = $this->getAudits();
        return $audits->count() > 1 ? $audits->slice($audits->count()-1, 1) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        // URGENT: Implement getTitle() method.
    }
}