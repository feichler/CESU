<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 11:14
 */

namespace Elektra\SeedBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

interface AuditableInterface
{
    /**
     * @param ArrayCollection
     */
    public function setAudits($audits);

    /**
     * @return ArrayCollection
     */
    public function getAudits();
}