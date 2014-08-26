<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\CrudBundle\Entity;

use Elektra\SeedBundle\Entity\EntityInterface as BaseInterface;

/**
 * Interface CRUDEntityInterface
 *
 * @package Elektra\SeedBundle\Entity
 *
 * @version 0.1-dev
 */
interface EntityInterface extends BaseInterface
{

    /**
     * Return the representative title of the entity
     *
     * @return string
     */
    public function getTitle();
}