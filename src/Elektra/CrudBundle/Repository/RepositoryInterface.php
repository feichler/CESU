<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\CrudBundle\Repository;

/**
 * Interface CRUDRepositoryInterface
 *
 * @package Elektra\SeedBundle\Repositories
 *
 * @version 0.1-dev
 */
interface RepositoryInterface
{

    /**
     * Get the count of all entries in this repository
     *
     * @param array|null $search
     * @param array|null $filters
     * @param array|null $order
     *
     * @return int
     */
    public function getCount($search = null, $filters = null, $order = null);

    /**
     * Get the entries defined by page and limit per page
     *
     * @param int        $page
     * @param int        $perPage
     * @param array|null $search
     * @param array|null $filters
     * @param array|null $order
     *
     * @return array
     */
    public function getEntries($page, $perPage, $search = null, $filters = null, $order = null);

    /**
     * Checks if an entry can be deleted (no references, no constraint violations, etc)
     *
     * @param mixed $entry
     *
     * @return bool
     */
    public function getCanDelete($entry);
}