<?php

namespace Elektra\ThemeBundle\Twig;

use Elektra\ThemeBundle\Theme\Theme;

class ThemeExtension extends \Twig_Extension
{

    /**
     * @var Theme
     */
    protected $theme;

    /**
     * Constructor
     *
     * @param Theme $theme
     */
    public function __construct(Theme $theme)
    {

        $this->theme = $theme;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {

        return "theme_extension";
    }

    /**
     * @inheritdoc
     */
    public function getGlobals()
    {

        return array('theme' => $this->theme);
    }

    public function initializeComplete()
    {

        $this->theme->setScript('jquery');
        $this->theme->setScript('bootstrap');
        $this->theme->setStylesheet('bootstrap');
        $this->theme->setStylesheet('fontAwesome');

        $this->theme->setSubTemplate('navbar', 'ElektraThemeBundle:Parts/Navigation:base-navbar.html.twig');
        $this->theme->setSubTemplate('footer', 'ElektraThemeBundle:Parts/Footer:base-footer.html.twig');
        $this->theme->setSubTemplate('messages', 'ElektraThemeBundle:Parts/Messages:base-messages.html.twig');
        $this->theme->setSubTemplate('messages-error', 'ElektraThemeBundle:Parts/Messages:base-messages-error.html.twig');
        $this->theme->setSubTemplate('messages-warning', 'ElektraThemeBundle:Parts/Messages:base-messages-warning.html.twig');
        $this->theme->setSubTemplate('messages-info', 'ElektraThemeBundle:Parts/Messages:base-messages-info.html.twig');
        $this->theme->setSubTemplate('messages-success', 'ElektraThemeBundle:Parts/Messages:base-messages-success.html.twig');
    }
}