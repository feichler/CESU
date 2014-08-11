<?php

namespace Elektra\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{

    public function loginAction(Request $request)
    {

        $site = $this->container->get('site');
        $site->initializeUserPage('Login', 'Login');

        return parent::loginAction($request);
    }

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