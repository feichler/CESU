<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Content;

/**
 * Class Content
 *
 * @package Elektra\ThemeBundle\Content
 *
 *          @version 0.1-dev
 */
abstract class Content
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string|null
     */
    protected $container;

    public function __construct($type)
    {

        $this->type      = $type;
        $this->content   = '';
        $this->container = null;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {

        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->prepareContent();

        return $this->content;
    }

    /**
     *
     */
    protected function prepareContent()
    {

        // NOTE override this method if some preparation based on the values is needed
        // NOTE container setting gets prepared here, so be sure to call parent::prepareContent after the overriding processing

        $tag = '';
        switch ($this->container) {
            case 'p':
            case 'paragraph':
                $tag = 'p';
                break;
            case 'div':
                $tag = 'div';
                break;
            case 'span':
                $tag = 'span';
                break;
            case 'b':
            case 'bold':
            case 'strong':
                $tag = 'strong';
                break;
            case 'i':
            case 'italic':
            case 'em':
                $tag = 'em';
                break;
        }

        if ($tag != '') {
            $this->content = '<' . $tag . '>' . $this->content . '</' . $tag . '>';
        }
    }

    /**
     * @param string $container
     */
    public function setContainer($container)
    {

        $this->container = $container;
    }

    /**
     * @return null|string
     */
    public function getContainer()
    {

        return $this->container;
    }

    /**
     * @return mixed
     */
    public function getType()
    {

        return $this->type;
    }
}