<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface AnnotableInterface
 *
 * @package Elektra\SeedBundle\Entity
 *
 * @version 0.1-dev
 */
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
    // CHECK: maybe an addNodes() method would be convenient
}