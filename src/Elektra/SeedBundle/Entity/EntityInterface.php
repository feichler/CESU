<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity;

/**
 * Interface CRUDEntityInterface
 *
 * @package Elektra\SeedBundle\Entity
 *
 * @version 0.1-dev
 */
interface EntityInterface
{

    /**
     * Return the id of the entry
     *
     * @return int
     */
    public function getId();
}