<?php

namespace Elektra\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @package Elektra\UserBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $lastName;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Elektra\UserBundle\Entity\Group")
     * @ORM\JoinTable(
     *      name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {

        parent::__construct();
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {

        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {

        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {

        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {

        return $this->lastName;
    }
}
