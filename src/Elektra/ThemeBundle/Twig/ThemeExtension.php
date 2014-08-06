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
    }
}