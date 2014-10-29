<?php

namespace Elektra\SeedBundle\Entity\Notes;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractEntity;
use Elektra\UserBundle\Entity\User;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Notes\NoteRepository")
 * @ORM\Table(name="notes")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class Note extends AbstractEntity
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
    public function getNoteId()
    {

        return $this->noteId;
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

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getNoteId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getTitle();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function ensureTimestamp()
    {

        if (!$this->getTimestamp()) {
            $this->setTimestamp(time());
        }
    }
}