<?php

namespace Elektra\UserBundle\Controller;

use Elektra\ThemeBundle\Page\Overrides\LanguageSimple;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{

    private function initialise($action)
    {

        $options = array();

        if ($action == 'login') {
            $options['language']['title']   = new LanguageSimple('lang.admin.pages.login.title', 'ElektraSite');
            $options['language']['heading'] = new LanguageSimple('lang.admin.pages.login.heading', 'ElektraSite');
        }

        $page = $this->container->get('page');
        $page->initialiseAdminPage('security', $action, $options);

        //        $theme = $this->container->get('site');
        //        $theme->initialiseUserPage($action);
        //        $theme->initialiseAdminPage($action, true);
    }

    public function loginAction(Request $request)
    {

        $this->initialise('login');
        //        $site = $this->container->get('site');
        //        $site->initializeUserPage('Login', 'Login');

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