<?php

namespace Elektra\ThemeBundle\Pagination;

use Symfony\Component\Routing\RouterInterface;

class Pagination
{

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct()
    {

        $this->parameters = array(
            'limit'     => 25, // TODO src make this a configurable parameter (config file + session)
            'page'      => 0,
            'count'     => 0,
            'prevLinks' => 5,
            'nextLinks' => 5,
        );
    }

    public function setRouter(RouterInterface $router)
    {

        $this->router = $router;
    }

    public function setLimit($limit)
    {

        $this->setParameter('limit', $limit);
        $this->refreshPages();
    }

    public function getLimit()
    {

        return $this->getParameter('limit');
    }

    public function setPage($page)
    {

        $this->setParameter('page', $page);
    }

    public function getPage()
    {

        return $this->getParameter('page');
    }

    public function setCount($count)
    {

        $this->setParameter('count', $count);
        $this->refreshPages();
    }

    public function getCount()
    {

        return $this->getParameter('count');
    }

    public function getParameter($name)
    {

        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        return null;
    }

    public function setParameter($name, $value)
    {

        $this->parameters[$name] = $value;
    }

    public function setRoute($route)
    {

        $this->setParameter('route', $route);
    }

    public function getPages()
    {

        return $this->getParameter('pages');
    }

    public function getFirst()
    {

        if ($this->getPage() > 1) {
            return $this->getPageLink(1);
        }

        return null;
    }

    public function getPrevious()
    {

        if ($this->getPage() > 1) {
            return $this->getPageLink($this->getPage() - 1);
        }

        return null;
    }

    public function getNext()
    {

        if ($this->getPage() < $this->getPages()) {
            return $this->getPageLink($this->getPage() + 1);
        }

        return null;
    }

    public function getLast()
    {

        if ($this->getPage() < $this->getPages()) {
            return $this->getPageLink($this->getPages());
        }

        return null;
    }

    public function getPreviousSpace()
    {

        $page      = $this->getPage();
        $prevLinks = $this->getParameter('prevLinks');

        $diff = $page - $prevLinks;

        if ($diff <= 1) {
            return false;
        }

        return true;
    }

    public function getPageLinks()
    {

        $links = array();

        $page      = $this->getPage();
        $prevLinks = $this->getParameter('prevLinks');
        for ($i = $prevLinks; $i > 0; $i--) {
            $linkTo = $page - $i;
            if ($linkTo >= 1) {
                $links[$linkTo] = $this->getPageLink($linkTo);
            }
        }

        $links[$page] = '';

        $nextLinks = $this->getParameter('nextLinks');
        for ($i = 1; $i <= $nextLinks; $i++) {
            $linkTo = $page + $i;
            if ($linkTo <= $this->getPages()) {
                $links[$linkTo] = $this->getPageLink($linkTo);
            }
        }

        return $links;
    }

    public function getNextSpace()
    {

        $page      = $this->getPage();
        $nextLinks = $this->getParameter('nextLinks');
        $max       = $this->getPages();

        $diff = $page + $nextLinks;

        if ($diff >= $max) {
            return false;
        }

        return true;
    }

    protected function getPageLink($page)
    {

        $link = $this->router->generate($this->getParameter('route'), array('page' => $page));

        return $link;
    }

    protected function refreshPages()
    {

        $count = $this->getCount();
        $limit = $this->getLimit();
        $pages = ceil($count / $limit);

        $this->setParameter('pages', $pages);
    }
}