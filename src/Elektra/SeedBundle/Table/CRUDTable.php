<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table;

use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SiteBundle\Navigator\Definition;
use Elektra\SiteBundle\Navigator\Navigator;
use Elektra\ThemeBundle\Table\Row;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CRUDTable
 *
 * @package Elektra\SeedBundle\Table
 *
 * @version 0.1-dev
 */
abstract class CRUDTable extends Table
{

    /**
     * @var Navigator
     */
    protected $navigator;

    /**
     * @var string
     */
    protected $definitionKey;

    /**
     * @var array
     */
    protected $allowedActions;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();

        $this->crudParams     = array();
        $this->allowedActions = array(
            'view'   => true,
            'add'    => true,
            'edit'   => true,
            'delete' => true,
        );

        // Set the default styling
        $this->getStyle()->setFullWidth();
        $this->getStyle()->setVariant('striped');
        $this->getStyle()->setVariant('bordered');
        $this->getStyle()->setVariant('responsive');
        // MINOR: set table styling to condensed?

    }

    /**
     * @param string $action
     *
     * @return bool
     */
    protected function isAllowed($action)
    {

        if (array_key_exists($action, $this->allowedActions)) {
            return $this->allowedActions[$action];
        }

        return false;
    }

    /**
     * @param string $action
     */
    protected function allowAction($action)
    {

        if (array_key_exists($action, $this->allowedActions)) {
            $this->allowedActions[$action] = true;
        }
    }

    /**
     * @param string $action
     */
    protected function disallowAction($action)
    {

        if (array_key_exists($action, $this->allowedActions)) {
            $this->allowedActions[$action] = false;
        }
    }

    public function setNavigator(Navigator $navigator, $definitionKey)
    {

        $this->navigator     = $navigator;
        $this->definitionKey = $definitionKey;
        $this->pagination->setNavigator($navigator, $definitionKey);
    }

    /**
     * @param array $entries
     */
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

    /**
     * @param Row $footer
     */
    protected function setupFooter(Row $footer)
    {

        // NOTE: default footer row implmentation - if needed, override it

        if ($this->isAllowed('add')) {

            $cell = $footer->addCell();
            $cell->setColumnSpan($this->getColumnCount());
            $cell->addClass('text-right');

            $link = $this->navigator->getLink($this->definitionKey, 'add');

            $cell->addActionContent('add', $link);
        }
    }

    /**
     * @param Row $header
     *
     */
    protected abstract function setupHeader(Row $header);

    /**
     * @param Row                 $content
     * @param CRUDEntityInterface $entry
     *
     */
    protected abstract function setupContentRow(Row $content, CRUDEntityInterface $entry);

    /**
     * //     * @param string $action
     * //     *
     * //     * @return string
     * //     */
    //    protected function getRoute($action)
    //    {
    //
    //        $route = $this->getRoutePrefix() . '_' . $action;
    //
    //        return $route;
    //    }

    /**
     * @param string   $route
     * @param int|null $id
     *
     * @return string
     */
    protected function generateLink($route, $id = null)
    {

        $params = array();
        if ($id !== null) {
            $params['id'] = $id;
        }

        $link = $this->navigator->getLink($this->definitionKey, $route, $params);

        return $link;
    }

    /**
     * @param Row             $row
     * @param EntityInterface $entry
     */
    protected function generateIdCell(Row $row, EntityInterface $entry)
    {

        $cell = $row->addCell();
        $cell->addClass('id');

        $cell->addHtmlContent($entry->getId());
    }

    /**
     * @param Row                $row
     * @param AuditableInterface $entry
     */
    protected function generateAuditCell(Row $row, AuditableInterface $entry)
    {

        $cell = $row->addCell();
        $cell->addClass('audits');
        // TODO implement audit display

    }

    /**
     * @param Row                 $row
     * @param CRUDEntityInterface $entry
     */
    protected function generateActionsCell(Row $row, CRUDEntityInterface $entry)
    {

        $cell = $row->addCell();
        $cell->addClass('actions');
        $cell->addClass('text-right');

        if ($this->isAllowed('edit')) {
            // TODO check if edit is possible (privileges)
            $editLink = $this->generateLink('edit', $entry->getId());
            $cell->addActionContent('edit', $editLink);
        }

        if ($this->isAllowed('delete')) {
            // TODO check if delete is possible (privileges & references)
            $deleteLink = $this->generateLink('delete', $entry->getId());
            $cell->addActionContent('delete', $deleteLink);
        }
    }
}