<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CompanyRepository")
 * @ORM\Table(name="companies")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="companyType",type="string")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: defined by inheriting class (name within inheriting)
 */
abstract class Company extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $companyId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var Collection CompanyLocation[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyLocation", mappedBy="company",
     * fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    protected $locations;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "companies_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "companyId", referencedColumnName = "companyId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "companies_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "companyId", referencedColumnName = "companyId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->locations = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getCompanyId()
    {

        return $this->companyId;
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
     * @param Collection CompanyLocation[] $locations
     */
    public function setLocations($locations)
    {

        $this->locations = $locations;
    }

    /**
     * @param CompanyLocation $location
     */
    public function addLocation(CompanyLocation $location)
    {

        $this->getLocations()->add($location);
    }

    /**
     * @return Collection CompanyLocation[]
     */
    public function getLocations()
    {

        return $this->locations;
    }

    /*************************************************************************
     * Other methods
     *************************************************************************/

    /**
     * @return Collection CompanyPerson[]
     */
    public function getPersons()
    {

        $persons   = new ArrayCollection();
        $locations = $this->getLocations();
        foreach ($locations as $location) {
            if ($location instanceof CompanyLocation) {

                foreach ($location->getPersons() as $person) {
                    $persons->add($person);
                }
            }
        }

        return $persons;
    }

    /**
     * @return CompanyLocation
     */
    public function getPrimaryLocation()
    {

        $locations = $this->getLocations();
        if ($locations instanceof Selectable) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq('isPrimary', true));
            $criteria->setMaxResults(1);

            $primaryLocation = $locations->matching($criteria)->first();

            return $primaryLocation;
        } else {
            throw new \RuntimeException('Cannot match within the locations');
        }

        // TODO remove old code after verification of new code
        //        $primaryLocation = $this->getLocations()->matching(Criteria::create()->where(Criteria::expr()
        //                                                                                             ->eq("isPrimary", true))
        //                                                                   ->setMaxResults(1))->first();
        //
        //        return $primaryLocation;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getCompanyId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getName();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none

}