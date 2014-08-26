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
 * Class Group
 *
 * @package Elektra\SiteBundle\Menu
 *
 * @version 0.1-dev
 */
class Group extends Item
{

    /**
     * @var array Item
     */
    protected $items;

    /**
     * @param string $title
     */
    public function __construct($title)
    {

        parent::__construct($title);

        $this->items = array();
    }

    /**
     * @param Item  $item
     * @param mixed $index
     */
    public function addItem(Item $item, $index = null)
    {

        $item->setParent($this);

        if (is_int($index)) {
            $this->items[$index] = $item;
        } else if (is_string($index) && $index == 'first') {
            array_unshift($this->items, $item);
        } else {
            // if not null or not 'first', ignore it (every other string will default to 'last' and any other type is ignored)
            $this->items[] = $item;
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {

        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function setLevels($level)
    {

        parent::setLevels($level);

        $nextLevel = $level + 1;
        foreach ($this->items as $item) {
            $item->setLevels($nextLevel);
        }
    }



    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {

        return 'ElektraSiteBundle:menu:group.html.twig';
    }
}