<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Pagination;

use Elektra\SiteBundle\Navigator\Navigator;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Pagination
 *
 * @package Elektra\ThemeBundle\Pagination
 *
 * @version 0.1-dev
 */
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

    /**
     * @var Navigator
     */
    protected $navigator;

    /**
     * @var string
     */
    protected $definitionKey;

    /**
     *
     */
    public function __construct()
    {

        $this->parameters = array(
            'limit'     => 25, // TODO make this a configurable parameter (config file + session)
            'page'      => 0,
            'count'     => 0,
            'prevLinks' => 5,
            'nextLinks' => 5,
        );
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {

        $this->router = $router;
    }

    public function setNavigator(Navigator $navigator, $definitionKey)
    {

        $this->navigator     = $navigator;
        $this->definitionKey = $definitionKey;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {

        $this->setParameter('limit', $limit);
        $this->refreshPages();
    }

    /**
     * @return int
     */
    public function getLimit()
    {

        return $this->getParameter('limit');
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {

        $this->setParameter('page', $page);
    }

    /**
     * @return int
     */
    public function getPage()
    {

        return $this->getParameter('page');
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {

        $this->setParameter('count', $count);
        $this->refreshPages();
    }

    /**
     * @return int
     */
    public function getCount()
    {

        return $this->getParameter('count');
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getParameter($name)
    {

        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParameter($name, $value)
    {

        $this->parameters[$name] = $value;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {

        $this->setParameter('route', $route);
    }

    /**
     * @return int
     */
    public function getPages()
    {

        return $this->getParameter('pages');
    }

    /**
     * @return null|string
     */
    public function getFirst()
    {

        if ($this->getPage() > 1) {
            return $this->getPageLink(1);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getPrevious()
    {

        if ($this->getPage() > 1) {
            return $this->getPageLink($this->getPage() - 1);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getNext()
    {

        if ($this->getPage() < $this->getPages()) {
            return $this->getPageLink($this->getPage() + 1);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getLast()
    {

        if ($this->getPage() < $this->getPages()) {
            return $this->getPageLink($this->getPages());
        }

        return null;
    }

    /**
     * @return bool
     */
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

    /**
     * @return array
     */
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

    /**
     * @return bool
     */
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

    /**
     * @param int $page
     *
     * @return string
     */
    protected function getPageLink($page)
    {

        $link = $this->navigator->getLink($this->definitionKey, 'browse', array('page' => $page));

        //        $link = $this->router->generate($this->getParameter('route'), array('page' => $page));

        return $link;
    }

    /**
     *
     */
    protected function refreshPages()
    {

        $count = $this->getCount();
        $limit = $this->getLimit();
        $pages = ceil($count / $limit);

        $this->setParameter('pages', $pages);
    }
}