<?php

namespace Elektra\SeedBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Elektra\SeedBundle\Entity\Notes\Note;
use Elektra\SiteBundle\Entity\EntityInterface;

interface AnnotableEntityInterface extends EntityInterface
{

    /**
     * Get the list of notes on this entity
     *
     * @return Collection Note[]
     */
    public function getNotes();

    /**
     * Set the list of notes on this entity
     *
     * @param Collection Note[] $notes
     *
     * @return void
     */
    public function setNotes($notes);

    /**
     * Add one note to the notes on this entity
     *
     * @param Note $note
     *
     * @return void
     */
    public function addNote(Note $note);

    /**
     * Get the last note from the list if the entity has notes
     *
     * @return Note|null
     */
    public function getLastNote();
}