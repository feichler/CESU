<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Page\Overrides\LanguageChain;
use Elektra\ThemeBundle\Page\Overrides\LanguageChoice;
use Elektra\ThemeBundle\Page\Overrides\LanguageSimple;
use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RequestController
 *
 * @package Elektra\SiteBundle\Controller
 *
 *          @version 0.1-dev
 */
class RequestController extends Controller
{

    /**
     * @param string $action
     * @param int $step
     */
    private function initialise($action, $step)
    {

        $options = $this->getInitialiseOptions();

        if ($step == 1) {
            $options['language']['title']   = new LanguageSimple('lang.site.pages.request.title', 'ElektraSite');
            $options['language']['heading'] = new LanguageSimple('lang.site.pages.request.heading', 'ElektraSite');
            $options['language']['section'] = new LanguageSimple('lang.site.pages.request.section', 'ElektraSite');
        }

        $page = $this->container->get('page');
        $page->initialiseSitePage('request', $action, $options);
    }

    /**
     * @param Request $request
     * @param string        $step
     * @param int        $number
     *
     * @return Response
     */
    public function indexAction(Request $request, $step, $number)
    {

        $this->initialise('index', $number);

        // TODO implement request controller functionality

        $table = new Table();
        $table->setId('table-id');

        $table->getStyle()->setVariant('condensed');
        $table->getStyle()->setVariant('responsive');
        $table->getStyle()->setVariant('striped');
        $table->getStyle()->setVariant('border');
        $table->getStyle()->setFullWidth();

        $header = $table->addHeader();
        $cell1  = $header->addCell();
        $cell1->setWidth('10', '%');
        $cell1->addHtmlContent('ID');
        $cell2 = $header->addCell();
        $cell2->addHtmlContent('Title');
        $cell3 = $header->addCell();
        $cell3->setWidth('150');
        $cell3->addHtmlContent('Something');

        $footer = $table->addFooter();
        $cell1  = $footer->addCell();
        $item   = $cell1->addHtmlContent('nothing here');
        $item->setContainer('strong');
        $cell1->addClass('text-right');
        $cell1->setColumnSpan($table->getColumnCount());

        $content1 = $table->addContent();
        $content1->setError();
        $cell1 = $content1->addCell();
        $cell1->setColumnSpan(2);

        $cell1->addHtmlContent('test');
        $cell3 = $content1->addCell();
        $cell3->setRowSpan(2);
        $cell3->addActionContent('add', 'somelink', array('text' => 'Custom Add'));

        $content2 = $table->addContent();
        $content2->setInfo();
        $cell1 = $content2->addCell();
        $cell1->setWarning();
        $cell1->addHtmlContent('1');
        $cell2 = $content2->addCell();
        $cell2->addActionContent('edit', 'editLink', array('confirmMsg' => 'Something', 'render' => 'link'));

        //        $row = $table->addContentRow();
        //        $row->setError();
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('test');
        //        $cell->setColumnSpan(2);
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('test1');
        //        $cell->setRowSpan(2);
        //        //        $cell->addContent('asdf');
        //        $cell->addClass('text-right');
        //        $cell->addHTMLContent('ASDF');
        //        $testLink = $this->get('router')->generate('ElektraSiteBundle_request_index', array('number' => 2));
        //        $cell->addActionContent('add', array($testLink, '', 'link'));
        //        $cell->addActionContent('edit', array($testLink, '', 'link'));
        //        $cell->addActionContent('delete', array($testLink, '', 'link'));
        //
        //        $cell->addActionContent('add', array($testLink, 'New'));
        //        $cell->addActionContent('edit', array($testLink));
        //        $cell->addActionContent('delete', array($testLink));
        //        $cell->addHTMLContent('ASDF1234');
        //        var_dump($testLink);
        //        $cell->addContent('{action:add:button|' . $testLink . '|New}');

        //        $row  = $table->addContentRow();
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('testXXX');
        //        $cell->setColumnSpan(2);
        //        $table->getStyle()->setCondensed();
        //        $table->getStyle()->setResponsive();
        //        $table->getStyle()->setStriped();
        //        $table->getStyle()->setBordered();
        //        $table->getStyle()->setFullWidth();
        //        $row  = $table->addHeaderRow();
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('H1');
        //        $cell->addHTMLContent('H1');
        //        $cell->setWidth('10', '%');
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('something');
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('bla');
        //        $row  = $table->addFooterRow();
        //        $cell = $row->addCell();
        //        $cell->addHTMLContent('nothing');
        //        $cell->addClass('text-right');
        //        $cell->setColumnSpan($table->getColumnCount());

        return $this->render('ElektraThemeBundle::base.html.twig', array('table' => $table));
    }
}