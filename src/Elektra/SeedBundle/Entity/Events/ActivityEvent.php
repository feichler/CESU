<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Event\ActivityEventRepository")
 * @ORM\Table(name="events_activity")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing?
 */
class ActivityEvent extends Event
{

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=true)
     */
    protected $person;

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
     * @param CompanyPerson $person
     */
    public function setPerson($person)
    {

        $this->person = $person;
    }

    /**
     * @return CompanyPerson
     */
    public function getPerson()
    {

        return $this->person;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // nothing

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}