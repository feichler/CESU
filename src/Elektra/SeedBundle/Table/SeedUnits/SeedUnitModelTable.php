<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\ThemeBundle\Element\Table;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

class SeedUnitModelTable extends Table
{

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router, $entries)
    {

        parent::__construct($router);
        $this->setRoutingPrefix('ElektraSeedBundle_seedunits_models');

        parent::defaultStyling();

        $this->defineHeader();
        $this->defineFooter();
        $this->addEntries($entries);
    }

    protected function defineHeader()
    {

        $header = $this->addHeaderRow();

        $idCell = $header->addCell();
        $idCell->setWidth('40', 'px');
        $idCell->addHTMLContent('ID');

        $modelCell = $header->addCell();
        $modelCell->addHTMLContent('Model');

        $auditCell = $header->addCell();
        $auditCell->setWidth('230', 'px');
        $auditCell->addHTMLContent('Created / Updated');

        $actionsCell = $header->addCell();
        $actionsCell->setWidth('145', 'px');
        $actionsCell->addHTMLContent('Actions');
    }

    protected function defineFooter()
    {

        $footer = $this->addFooterRow();

        $footerCell = $footer->addCell();
        $footerCell->addClass('text-right');
        $footerCell->setColumnSpan(4);

        $link = $this->router->generate($this->getRouteName('add'));
        $footerCell->addActionContent('add', array($link));
    }

    protected function addEntries($entries)
    {

        foreach ($entries as $entry) {
            if ($entry instanceof SeedUnitModel) {
                $row = $this->addContentRow();

                $idCell = $row->addCell();
                $idCell->addClass('text-right');
                $idCell->addHTMLContent($entry->getId());

                $viewLink  = $this->router->generate($this->getRouteName('view'), array('id' => $entry->getId()));
                $modelCell = $row->addCell();
                $modelCell->addActionContent('view', array($viewLink, $entry->getName()));
                $modelCell->addHTMLContent('<br />');
                $modelCell->addHTMLContent($entry->getDescription());

                $auditCell = $row->addCell();
                if ($entry->getAudits() != null) {
                    $audits = $entry->getAudits();
                    $created = $entry->getCreationAudit();
                    $modified = $entry->getLastModifiedAudit();
                    //                    $auditCell->addHTMLContent($audit->getCreatedBy()->getUsername());
                    if ($created != null) {
                    $auditCell->addHTMLContent(date('Y-m-d H:i:s O', $created->getTimestamp()));
                    }
//                    if($audits->getTimestamp() != null) {
//                        $auditCell->addHTMLContent($audits->getTimestamp());
//                    } else {
//                        $auditCell->addHTMLContent('NULL');
//                    }
                } else {
                    $auditCell->addHTMLContent('Unknown');
                }

                // TODO src: check if the entry can be deleted (privileges, references)
                // TODO src: check if the entry can be edited (privileges)
                $actionsCell = $row->addCell();
                $actionsCell->addClass('text-right');
                $editLink   = $this->router->generate($this->getRouteName('edit'), array('id' => $entry->getId()));
                $deleteLink = $this->router->generate($this->getRouteName('delete'), array('id' => $entry->getId()));
                $actionsCell->addActionContent('edit', array($editLink));
                $actionsCell->addActionContent('delete', array($deleteLink));
            }
        }
    }
}