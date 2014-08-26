<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Notes;

use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\UserBundle\Entity\User;

/**
 * Class Note
 *
 * @package Elektra\SeedBundle\Entity\Notes
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="notes")
 */
class Note implements CrudInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $noteId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Elektra\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->noteId;
    }

    /**
     * @return int
     */
    public function getNoteId()
    {

        return $this->noteId;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {

        return $this->text;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {

        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {

        return $this->timestamp;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {

        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {

        return $this->user;
    }
}