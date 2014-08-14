<?php

namespace Elektra\SeedBundle\Repositories;

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
    public function getEntries($page, $perPage);

    /**
     * Checks if an entry can be deleted (no references, no constraint violations, etc)
     *
     * @param mixed $entry
     *
     * @return bool
     */
    public function getCanDelete($entry);
}