<?php

namespace Elektra\SeedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Elektra\SeedBundle\Entity\Notes\Note;
use Elektra\SiteBundle\Entity\AbstractEntity;

abstract class AbstractAnnotableEntity extends AbstractEntity implements AnnotableEntityInterface
{

    /**
     * @var Collection Note[]
     */
    protected $notes;

    /**
     * Constructor
     *
     * @inheritdoc
     */
    public function __construct()
    {

        parent::__construct();

        $this->notes = new ArrayCollection();
    }

    /*************************************************************************
     * AnnotableInterface
     *************************************************************************/

    /**
     * @inheritdoc
     */
    public function getNotes()
    {

        return $this->notes;
    }

    /**
     * @inheritdoc
     */
    public function setNotes($notes)
    {

        $this->notes = $notes;
    }

    /**
     * @inheritdoc
     */
    public function addNote(Note $note)
    {

        $this->getNotes()->add($note);
    }

    /**
     * @inheritdoc
     */
    public function getLastNote()
    {

        /** @var Note $lastNote */
        $lastNote = null;

        foreach ($this->getNotes() as $note) {
            if ($note instanceof Note) {
                if ($lastNote === null) {
                    $lastNote = $note;
                } else {
                    if ($lastNote->getTimestamp() < $note->getTimestamp()) {
                        $lastNote = $note;
                    }
                }
            }
        }

        return $lastNote;
    }
}