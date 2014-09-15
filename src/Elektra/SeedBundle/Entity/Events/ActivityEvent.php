<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;

/**
 * Class ActivityEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="activityEvents")
 */
class ActivityEvent extends StatusEvent
{

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=true)
     */
    protected $person;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\CompanyPerson $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\CompanyPerson
     */
    public function getPerson()
    {
        return $this->person;
    }
}