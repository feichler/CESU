<?php

namespace Elektra\SeedBundle\Table;

use Elektra\ThemeBundle\Table\Cell;
use Elektra\ThemeBundle\Table\Row;
use Symfony\Component\Routing\RouterInterface;

abstract class CRUDTable extends Table
{

    /**
     * @var RouterInterface
     */
    protected $router;

    protected $crudParams;

    public function __construct()
    {

        parent::__construct();

        // Set the default styling

        $this->getStyle()->setFullWidth();
        $this->getStyle()->setVariant('striped');
        $this->getStyle()->setVariant('bordered');
        $this->getStyle()->setVariant('responsive');
        // TODO src: condensed?

        $this->setupType();
    }

    public function setRouter(RouterInterface $router)
    {

        $this->router = $router;
    }

    public function prepare($entries)
    {

        $headerRow = $this->addHeader();
        $this->setupHeader($headerRow);

        $footerRow = $this->addFooter();
        $this->setupFooter($footerRow);

        foreach ($entries as $entry) {
            $contentRow = $this->addContent();
            $this->setupContentRow($contentRow, $entry);
        }
    }

    protected function getRoutePrefix()
    {

        $prefix = $this->getParam('routePrefix');

        if ($prefix == '' || $prefix === null) {
            throw new \InvalidArgumentException('Parameter "routePrefix" is missing');
        }

        return $prefix;
    }

    public function getParam($type)
    {

        if (array_key_exists($type, $this->params)) {
            return $this->params[$type];
        }

        return null;
    }

    public function setParam($type, $value)
    {

        $this->params[$type] = $value;
    }

    protected abstract function setupType();

    protected function setupFooter(Row $footer)
    {

        // NOTE: default footer row implmentation - if needed, override it

        $link = $this->router->generate($this->getRoute('add'));

        $cell = $footer->addCell();
        $cell->setColumnSpan($this->getColumnCount());
        $cell->addClass('text-right');
        $cell->addActionContent('add', $link);
    }

    protected abstract function setupHeader(Row $header);

    protected abstract function setupContentRow(Row $content, $entry);

    protected function getRoute($action)
    {

        $route = $this->getRoutePrefix() . '_' . $action;

        return $route;
    }

    protected function generateLink($route, $id = null)
    {

        $params = array();
        if ($id !== null) {
            $params['id'] = $id;
        }

        $link = $this->router->generate($route, $params);

        return $link;
    }

    protected function generateAuditCell(Row $row, $entry)
    {

        $cell = $row->addCell();
        $cell->addClass('audits');
        // TODO implement audit display

    }

    protected function generateActionsCell(Row $row, $entry)
    {

        $cell = $row->addCell();
        $cell->addClass('actions');
        $cell->addClass('text-right');

        // TODO check if edit is possible (privileges)
        $editLink = $this->generateLink($this->getRoute('edit'), $entry->getId());
        $cell->addActionContent('edit', $editLink);

        // TODO check if delete is possible (privileges & references)
        $deleteLink = $this->generateLink($this->getRoute('delete'), $entry->getId());
        $cell->addActionContent('delete', $deleteLink);
    }
}