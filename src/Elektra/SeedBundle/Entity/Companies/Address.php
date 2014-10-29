<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Notes\Note;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\AddressRepository")
 * @ORM\Table("addresses")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class Address extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $addressId;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="countryId")
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    protected $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $street1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $street2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $street3;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "addresses_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "addressId", referencedColumnName = "addressId",
     *      onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "addresses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "addressId", referencedColumnName = "addressId")},
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
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getAddressId()
    {

        return $this->addressId;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {

        $this->country = $country;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {

        return $this->country;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {

        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {

        return $this->state;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {

        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {

        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {

        return $this->postalCode;
    }

    /**
     * @param string $street1
     */
    public function setStreet1($street1)
    {

        $this->street1 = $street1;
    }

    /**
     * @return string
     */
    public function getStreet1()
    {

        return $this->street1;
    }

    /**
     * @param string $street2
     */
    public function setStreet2($street2)
    {

        $this->street2 = $street2;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {

        return $this->street2;
    }

    /**
     * @param string $street3
     */
    public function setStreet3($street3)
    {

        $this->street3 = $street3;
    }

    /**
     * @return string
     */
    public function getStreet3()
    {

        return $this->street3;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getAddressId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        // URGENT - displayName for address entity
        return '???';
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}