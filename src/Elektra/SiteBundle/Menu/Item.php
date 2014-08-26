<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Menu;

/**
 * Class Item
 *
 * @package Elektra\SiteBundle\Menu
 *
 * @version 0.1-dev
 */
class Item
{

    /**
     * @var Item
     */
    protected $parent;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $disabled;

    /**
     * @param string $title
     * @param string $link
     */
    public function __construct($title, $link = '#')
    {

        $this->parent   = null;
        $this->title    = $title;
        $this->link     = $link;
        $this->active   = false;
        $this->disabled = false;
    }

    /**
     * @param Item $parent
     */
    public function setParent(Item $parent)
    {

        $this->parent = $parent;
    }

    /**
     * @return Item|null
     */
    public function getParent()
    {

        return $this->parent;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * @param int $level
     */
    public function setLevels($level)
    {

        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {

        return $this->level;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {

        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {

        return $this->link;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {

        $this->active = $active;
        if ($this->parent !== null) {
            $this->parent->setActive($active);
        }
    }

    /**
     * @return bool
     */
    public function getActive()
    {

        return $this->active;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled($disabled)
    {

        $this->disabled = $disabled;
    }

    /**
     * @return bool
     */
    public function getDisabled()
    {

        return $this->disabled;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {

        return 'ElektraSiteBundle:menu:item.html.twig';
    }
}