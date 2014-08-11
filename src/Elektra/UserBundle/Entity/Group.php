<?php

namespace Elektra\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Group
 *
 * @package Elektra\UserBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup {

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}