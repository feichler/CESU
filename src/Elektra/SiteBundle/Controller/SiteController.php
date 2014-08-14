<?php

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Page\Overrides\LanguageSimple;
use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{

    private function initialise($action)
    {

        $options = $this->getInitialiseOptions();

        $options['language']['title']   = new LanguageSimple('lang.site.pages.' . $action . '.title', 'ElektraSite');
        $options['language']['heading'] = new LanguageSimple('lang.site.pages.' . $action . '.heading', 'ElektraSite');
        $options['language']['section'] = new LanguageSimple('lang.site.pages.' . $action . '.section', 'ElektraSite');

        $page = $this->container->get('page');
        $page->initialiseSitePage('site', $action, $options);
    }

    public function termsAction(Request $request)
    {

        $this->initialise('terms');

        // TODO define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Site:terms.html.twig');
    }

    public function aboutAction(Request $request)
    {

        $this->initialise('about');

        // TODO define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Site:about.html.twig');
    }
}