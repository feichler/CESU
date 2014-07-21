<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContactInfo
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="contact_infos")
 */
class ContactInfo
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $contactInfoId;

    /**
     * @ORM\ManyToOne(targetEntity="Person",inversedBy="contactInfos")
     * @ORM\JoinColumn(name="personId",referencedColumnName="personId")
     */
    protected $person;
}