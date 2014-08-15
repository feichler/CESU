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
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Interface AuditableInterface
 *
 * @package Elektra\SeedBundle\Entity
 *
 *          @version 0.1-dev
 */
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

    /**
     * @return Audit
     */
    public function getCreationAudit();

    /**
     * @return Audit
     */
    public function getLastModifiedAudit();
}