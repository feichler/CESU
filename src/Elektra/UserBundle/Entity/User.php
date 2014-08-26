<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class User
 *
 * @package Elektra\UserBundle\Entity
 *
 * @version 0.1-dev
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
     *
     */
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
