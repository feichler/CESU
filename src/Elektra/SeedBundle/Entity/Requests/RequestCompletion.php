<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Companies\Company;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

/**
 * Class RequestCompletion
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Requests\RequestCompletionRepository")
 * @ORM\Table(name="requestCompletion")
 */
class RequestCompletion implements AuditableInterface, AnnotableInterface, CRUDEntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestCompletionId;

    /**
     * @var Request
     *
     * @ORM\OneToOne(targetEntity="Request", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="requestId", referencedColumnName="requestId", nullable=false)
     */
    protected $request;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Company", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="companyId", referencedColumnName="companyId")
     */
    protected $company;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requesterPersonId", referencedColumnName="personId")
     */
    protected $requesterPerson;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="receiverPersonId", referencedColumnName="personId")
     */
    protected $receiverPerson;

    /**
     * @var CompanyLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyLocation", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="shippingLocationId", referencedColumnName="locationId")
     */
    protected $shippingLocation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="requestCompletion", fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "requestcompletions_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "requestCompletionId", referencedColumnName = "requestCompletionId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "requestcompletions_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "requestCompletionId", referencedColumnName = "requestCompletionId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {

        $this->notes  = new ArrayCollection();
        $this->audits = new ArrayCollection();
        $this->seedUnits = new ArrayCollection();
    }

    /**
     * @param CompanyPerson $receiverPerson
     */
    public function setReceiverPerson($receiverPerson)
    {

        $this->receiverPerson = $receiverPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getReceiverPerson()
    {

        return $this->receiverPerson;
    }

    /**
     * @param CompanyPerson $requesterPerson
     */
    public function setRequesterPerson($requesterPerson)
    {

        $this->requesterPerson = $requesterPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getRequesterPerson()
    {

        return $this->requesterPerson;
    }

    /**
     * @param CompanyLocation $shippingLocation
     */
    public function setShippingLocation($shippingLocation)
    {

        $this->shippingLocation = $shippingLocation;
    }

    /**
     * @return CompanyLocation
     */
    public function getShippingLocation()
    {

        return $this->shippingLocation;
    }

    /**
     * @param ArrayCollection $seedUnits
     */
    public function setSeedUnits($seedUnits)
    {

        $this->seedUnits = $seedUnits;
    }

    /**
     * @return ArrayCollection
     */
    public function getSeedUnits()
    {

        return $this->seedUnits;
    }

    /**
     * Return the representative title of the entity
     *
     * @return string
     */
    public function getTitle()
    {

        return $this->getRequest()->getRequestNumber();
    }

    /**
     * Return the id of the entry
     *
     * @return int
     */
    public function getId()
    {

        return $this->getRequestCompletionId();
    }

    /**
     * {@inheritdoc}
     */
    public function setNotes($notes)
    {

        $this->notes = $notes;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotes()
    {

        return $this->notes;
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

        $audits = $this->getAudits()->slice(0, 1);
        return $audits[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {

        $audits = $this->getAudits();
        if ($audits->count() > 1) {
            $audits = $audits->slice($audits->count() - 1, 1);

            return $audits[0];
        }

        return null;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Requests\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Requests\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param int $requestCompletionId
     */
    public function setRequestCompletionId($requestCompletionId)
    {
        $this->requestCompletionId = $requestCompletionId;
    }

    /**
     * @return int
     */
    public function getRequestCompletionId()
    {
        return $this->requestCompletionId;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}