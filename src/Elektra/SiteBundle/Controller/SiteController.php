<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Page\Overrides\LanguageSimple;
use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SiteController
 *
 * @package Elektra\SiteBundle\Controller
 *
 * @version 0.1-dev
 */
class SiteController extends Controller
{

    /**
     * @param string $action
     */
    private function initialise($action)
    {

        $options = $this->getInitialiseOptions();

        $options['language']['title']   = new LanguageSimple('lang.site.pages.' . $action . '.title', 'ElektraSite');
        $options['language']['heading'] = new LanguageSimple('lang.site.pages.' . $action . '.heading', 'ElektraSite');
        $options['language']['section'] = new LanguageSimple('lang.site.pages.' . $action . '.section', 'ElektraSite');

        $page = $this->container->get('page');
        $page->initialiseSitePage('site', $action, $options);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function termsAction(Request $request)
    {

        $this->initialise('terms');

        // TODO define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Site:terms.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function aboutAction(Request $request)
    {

        $this->initialise('about');

        // TODO define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Site:about.html.twig');
    }
}