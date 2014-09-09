<?php

namespace Elektra\CrudBundle\Twig;

use Elektra\CrudBundle\Table\Table;
use Elektra\SiteBundle\Site\Base;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class TableExtension extends \Twig_Extension
{

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'table_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {

        return array(
            'table' => new \Twig_SimpleFunction('table', array($this, 'renderTable'), array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true)),
        );
    }

    public function getFilters()
    {

        return array(
            'getClass' => new \Twig_SimpleFilter('getClass', 'get_class'),
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param array             $context
     * @param Table             $table
     *
     * @return string
     */
    public function renderTable(\Twig_Environment $environment, $context, Table $table)
    {

        $renderer = $this->getRendererFromContext($context);

        $baseTemplate     = 'ElektraCrudBundle:table:table.html.twig';
        $specificTemplate = $table->getTemplate();

        if ($renderer->exists($specificTemplate)) {
            $template = $environment->loadTemplate($specificTemplate);
        } else {
            $template = $environment->loadTemplate($baseTemplate);
        }

        return $template->renderBlock('table', array_merge($context, array('table' => $table)));

        return '';
    }

    /**
     * @param array $context
     *
     * @return EngineInterface
     * @throws \RuntimeException
     */
    private function getRendererFromContext($context)
    {

        if (array_key_exists('siteBase', $context)) {
            $siteBase = $context['siteBase'];
            if ($siteBase instanceof Base) {
                return $siteBase->getTemplateEngine();
            }
        }

        throw new \RuntimeException('Could not get renderer from context');
    }
}