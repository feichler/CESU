<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Crud\Navigator;

class Pagination
{

    /**
     * @var Table
     */
    protected $table;

    protected $actualPage;

    protected $limit;

    protected $linkCount = 3;

    public function __construct(Table $table)
    {

        $this->table = $table;
        $this->limit = 20; // TODO make parameter

        // URGENT embedded functionality
        //        if ($this->table->getCrud()->isEmbedded()) {
        //            $this->limit = 150;
        //        }
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

        $route= $this->table->getCrud()->getLinker()->getActiveRoute();
        $navigator = $this->table->getCrud()->getService('navigator');

        if ($navigator instanceof Navigator) {
            if ($route == 'request.seedUnit.add') {
                $routeName = $this->table->getCrud()->getLinker()->getActiveRoute();
                $id        = $this->table->getCrud()->getRequest()->get('id');
                $link      = $navigator->getLinkFromRoute($routeName, array('id' => $id, 'page' => $page));
            } else {
                // URGENT get the correct link here
                $link = $navigator->getLink($this->table->getCrud()->getDefinition(), 'browse', array('page' => $page));
            }
        } else {
            $link = '';
        }

        return $link;
    }

    public function getNavigation()
    {

        $pages = array();

        for ($i = $this->linkCount; $i > 0; $i--) {
            $linkTo = $this->getPage() - $i;
            if ($linkTo >= 1) {
                $pages[] = $linkTo;
            }
        }

        $pages[] = $this->getPage();

        for ($i = 1; $i <= $this->linkCount; $i++) {
            $linkTo = $this->getPage() + $i;
            if ($linkTo <= $this->getMaxPage()) {
                $pages[] = $linkTo;
            }
        }

        return $pages;
    }

    public function getNextSpace()
    {

        $diff = $this->getPage() + $this->linkCount + 1;

        if ($diff >= $this->getMaxPage()) {
            return false;
        }

        return true;
    }

    public function getPrevSpace()
    {

        $diff = $this->getPage() - $this->linkCount - 1;

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
        if ($maxPage == 0) {
            $maxPage = 1;
        }

        return $maxPage;
    }

    public function getLinkCount()
    {

        return $this->linkCount;
    }
}