<?php

namespace Aurealis\ThemeBundle\Twig;

class MessageExtension extends \Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {

        return 'message_extension';
    }

    public function getFilters()
    {

        $filters = parent::getFilters();

        $filters[] = new \Twig_SimpleFilter('msg', array($this, 'messageFilter'), array('is_safe' => array('html')));

        return $filters;
    }

    public function messageFilter($message)
    {

        $filtered = preg_replace('/:(.*):/', "<strong>\\1</strong>", $message);

        return $filtered;
    }
}