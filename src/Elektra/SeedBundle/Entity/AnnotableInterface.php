<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 11:14
 */

namespace Elektra\SeedBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

interface AnnotableInterface
{
    /**
     * @param ArrayCollection $notes
     */
    public function setNotes($notes);

    /**
     * @return ArrayCollection
     */
    public function getNotes();
}