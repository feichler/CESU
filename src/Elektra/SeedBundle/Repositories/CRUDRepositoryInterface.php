<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories;

/**
 * Interface CRUDRepositoryInterface
 *
 * @package Elektra\SeedBundle\Repositories
 *
 * @version 0.1-dev
 */
interface CRUDRepositoryInterface
{

    /**
     * Get the count of all entries in this repository
     *
     * @return int
     */
    public function getCount();

    /**
     * Get the entries defined by page and limit per page
     *
     * @param int $page
     * @param int $perPage
     *
     * @return array
     */
    public function getEntries($page, $perPage, $filters = array());

    /**
     * Checks if an entry can be deleted (no references, no constraint violations, etc)
     *
     * @param mixed $entry
     *
     * @return bool
     */
    public function getCanDelete($entry);
}