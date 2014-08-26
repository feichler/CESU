<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Navigator\Navigator;

class Pagination
{

    /**
     * @var Table
     */
    protected $table;

    protected $actualPage;

    protected $limit;

    public function __construct(Table $table)
    {

        $this->table = $table;
        $this->limit = 25; // TODO make parameter

        if ($this->table->getCrud()->isEmbedded()) {
            $this->limit = 150;
        }
    }

    public function setPage($page)
    {

        $this->actualPage = $page;
    }

    public function getPage()
    {

        return $this->actualPage;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {

        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {

        return $this->limit;
    }

    public function getPageLink($page)
    {

        $navigator = $this->table->getCrud()->getService('navigator');
        if ($navigator instanceof Navigator) {
            $link = $navigator->getLink($this->table->getCrud()->getDefinition(), 'browse', array('page' => $page));
        }

        return $link;
    }

    protected $links = 3;

    public function getNavigation()
    {

        $pages = array();

        for ($i = $this->links; $i > 0; $i--) {
            $linkTo = $this->getPage() - $i;
            if ($linkTo >= 1) {
                $pages[] = $linkTo;
            }
        }

        $pages[] = $this->getPage();

        for ($i = 1; $i <= $this->links; $i++) {
            $linkTo = $this->getPage() + $i;
            if ($linkTo <= $this->getMaxPage()) {
                $pages[] = $linkTo;
            }
        }

        return $pages;
    }

    public function getNextSpace()
    {

        $diff = $this->getPage() + $this->links + 1;

        if ($diff >= $this->getMaxPage()) {
            return false;
        }

        return true;
    }

    public function getPrevSpace()
    {

        $diff = $this->getPage() - $this->links - 1;

        if ($diff <= 1) {
            return false;
        }

        return true;
    }

    public function getMaxPage()
    {

        static $maxPage = -1;

        if ($maxPage == -1) {
            $maxPage = ceil($this->table->getEntryCount() / $this->limit);
        }

        return $maxPage;
    }

    public function hasPrevious()
    {

        if ($this->getPage() > 1) {
            return true;
        }

        return false;
    }

    public function getPrevious()
    {

        if ($this->getPage() > 1) {
            return $this->getPage() - 1;
        }

        return 0;
    }
}