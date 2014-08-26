<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 *
 * @package Elektra\UserBundle\Controller
 *
 * @version 0.1-dev
 */
class SecurityController extends BaseController
{

    /**
     * @param $action
     */
    private function initialise($action)
    {

        $siteBase   = $this->container->get('siteBase');
        $controller = 'ElektraUser:Security';

        $siteBase->initialisePage($controller, $action, 'security.' . $action);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {

        $this->initialise('login');

        return parent::loginAction($request);
    }

    /**
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {

        $error = $data['error'];

        if ($error != '') {
            $errorTrans = $this->container->get('translator')->trans($error, array(), 'FOSUserBundle');
            $this->container->get('session')->getFlashBag()->add('error', $errorTrans);
        }
        $template = sprintf('ElektraUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}