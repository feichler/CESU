<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Twig;

use Elektra\SiteBundle\Navigator\Navigator;

/**
 * Class NavigatorExtension
 *
 * @package Elektra\SeedBundle\Twig
 *
 * @version 0.1-dev
 */
class NavigatorExtension extends \Twig_Extension
{

    /**
     * @var Navigator
     */
    private $navigator;

    /**
     * @param Navigator $navigator
     */
    public function __construct(Navigator $navigator)
    {

        $this->navigator = $navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'navigator_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {

        return array(
            'navigator' => $this->navigator,
        );
    }
}