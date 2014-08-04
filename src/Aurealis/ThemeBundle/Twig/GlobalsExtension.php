<?php


namespace Aurealis\ThemeBundle\Twig;

class GlobalsExtension extends \Twig_Extension
{

    protected $menu;

    protected $page;

    protected $pagination;

    public function __construct($page, $menu, $pagination)
    {

        $this->page       = $page;
        $this->menu       = $menu;
        $this->pagination = $pagination;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {

        return 'globals_extension';
    }

    public function getGlobals()
    {

        return array(
            'theme_page'       => $this->page,
            'theme_pagination' => $this->pagination,
            'theme_menu'       => $this->menu
        );
    }
}