<?php

namespace Aurealis\ThemeBundle\Pagination;

class Pagination
{

    /**
     * @var string
     *
     * Route name for the pagination
     */
    protected $route;

    /**
     * @var string
     *
     * Route variable key for page numbers. Default "page"
     */
    protected $routePageKey = 'page';

    /**
     * @var int
     *
     * Entries per page. Default: 25
     */
    protected $perPage = 25;

    /**
     * @var array
     *
     * Options for the "select number of entries".
     */
    protected $perPageOptions = array();

    /**
     * @var array
     *
     * Default options for the "select number of entries" - this options get chosen if no other per page options are defined
     */
    protected $defaultPerPageOptions = array(5, 10, 25, 50, 100);

    /**
     * @var int
     *
     * Actual page
     */
    protected $pageNumber = 1;

    /**
     * @var int
     *
     * Total number of entries
     */
    protected $totalEntries = 0;

    protected $maxNavigation = 4;

    public function __construct()
    {
    }

    /* ##################################################################### *
     *  Setters / Includers / Adders
     * ##################################################################### */

    public function setRouteOptions($route, $routeKey = null)
    {

        $this->route = $route;
        if ($routeKey !== null) {
            $this->routePageKey = $routeKey;
        }
    }

    public function setPerPage($perPage)
    {

        $this->perPage = $perPage;
    }

    public function addPerPageOption($option)
    {

        $this->perPageOptions[] = $option;
    }

    public function setPageNumber($pageNumber)
    {

        $this->pageNumber = $pageNumber;
    }

    public function setTotalEntries($totalEntries)
    {

        $this->totalEntries = $totalEntries;
    }

    public function setMaxNavigation($maxNavigation)
    {

        $this->maxNavigation = $maxNavigation;
    }

    /* ##################################################################### *
     *  Getters
     * ##################################################################### */

    public function getRoute()
    {

        return $this->route;
    }

    public function getRoutePageKey()
    {

        return $this->routePageKey;
    }

    public function getPerPage()
    {

        return $this->perPage;
    }

    public function getPerPageOptions()
    {

        if (empty($this->perPageOptions)) {
            return $this->defaultPerPageOptions;
        }

        return $this->perPageOptions;
    }

    public function getPageNumber()
    {

        return $this->pageNumber;
    }

    public function getTotalPages()
    {

        $totalPages = ceil($this->totalEntries / $this->perPage);

        return $totalPages;
    }

    public function getTotalEntries()
    {

        return $this->totalEntries;
    }
}