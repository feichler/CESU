<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 *
 * @package Elektra\SiteBundle\Controller
 *
 * @version 0.1-dev
 */
class AdminController extends Controller
{

    /**
     * @param string $action
     */
    private function initialise($action)
    {

        $options = $this->getInitialiseOptions();

        // TRANSLATE add language overrides

        $page = $this->container->get('page');
        $page->initialiseAdminPage('admin', $action, $options);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {

        $this->initialise('index');

        // TODO define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Admin:index.html.twig');
    }
}