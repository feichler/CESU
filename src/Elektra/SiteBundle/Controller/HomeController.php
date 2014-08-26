<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as HTTPRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 * @package Elektra\SiteBundle\Controller
 *
 * @version 0.1-dev
 */
class HomeController extends Controller
{

    /**
     * @param string $action
     */
    private function initialise($action)
    {

        $siteBase   = $this->get('siteBase');
        $controller = 'ElektraSite:Home';

        $siteBase->initialisePage($controller, $action, 'home.' . $action);
    }

    /**
     * @param HTTPRequest $request
     *
     * @return Response
     */
    public function indexAction(HTTPRequest $request)
    {

        $this->initialise('index');

        return $this->render('ElektraSiteBundle:Home:index.html.twig');
    }
}
