<?php

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Element\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends Controller
{

    public function stepsAction(Request $request, $step, $number)
    {

        $this->container->get('elektra.twig.theme_extension')->initializeComplete();
        $bag = $this->container->get('session')->getFlashBag();

        //                $bag->add('info','asdf1234');
        //                $bag->add('error',"asdf");
        //                $bag->add('error','asdf2');
        //                $bag->add('warning','asdf');
        //                $bag->add('info','asdf');
        //                $bag->add('success','asdf');

        $theme = $this->container->get('theme');
        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:request-navbar.html.twig');
        //        $theme->resetSubTemplate('navbar');
        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:request-footer.html.twig');
        // TODO src: set the correct brand name or remove
        $theme->setPageVar('navbar.brand.name', 'TODO: short name');
        // TODO src: set the correct brand route or remove
        //        $theme->setPageVar('navbar.brand.route', 'TODO');
        $theme->setPageVar('heading', 'Cisco ASA with FirePOWER Services');

        $table = new Table();
        $table->getStyle()->setCondensed();
        $table->getStyle()->setResponsive();
        $table->getStyle()->setStriped();
        $table->getStyle()->setBordered();
        $table->getStyle()->setFullWidth();
        $row  = $table->addHeaderRow();
        $cell = $row->addCell();
        $cell->addHTMLContent('H1');
        $cell->addHTMLContent('H1');
        $cell->setWidth('10', '%');
        $cell = $row->addCell();
        $cell->addHTMLContent('something');
        $cell = $row->addCell();
        $cell->addHTMLContent('bla');
        $row  = $table->addFooterRow();
        $cell = $row->addCell();
        $cell->addHTMLContent('nothing');
        $cell->addClass('text-right');
        $cell->setColumnSpan($table->getColumnCount());
        $row = $table->addContentRow();
        $row->setError();
        $cell = $row->addCell();
        $cell->addHTMLContent('test');
        $cell->setColumnSpan(2);
        $cell = $row->addCell();
        $cell->addHTMLContent('test1');
        $cell->setRowSpan(2);
        //        $cell->addContent('asdf');
        $cell->addClass('text-right');
        $cell->addHTMLContent('ASDF');
        $testLink = $this->get('router')->generate('seedunit_request', array('number' => 2));
        $cell->addActionContent('add', array($testLink, '', 'link'));
        $cell->addActionContent('edit', array($testLink, '', 'link'));
        $cell->addActionContent('delete', array($testLink, '', 'link'));

        $cell->addActionContent('add', array($testLink, 'New'));
        $cell->addActionContent('edit', array($testLink));
        $cell->addActionContent('delete', array($testLink));
        $cell->addHTMLContent('ASDF1234');
        //        var_dump($testLink);
        //        $cell->addContent('{action:add:button|' . $testLink . '|New}');

        $row  = $table->addContentRow();
        $cell = $row->addCell();
        $cell->addHTMLContent('testXXX');
        $cell->setColumnSpan(2);
        //        $table->getHead()->addColumn('Test');
        //        $table->getHead()->addColumn('Test1',null,2);
        //        $table->getHead()->addColumn('X','10%');
        //        $table->addTableStyle(Table::STYLE_STRIPED);
        //        $table->addTableStyle(Table::STYLE_BORDERED);
        //        $table->addTableStyle(Table::STYLE_HOVERROWS);
        //        $table->addTableStyle(Table::STYLE_CONDENSED);
        //$table->setFullWidth();
        //        $table               = new \stdClass();
        //        $table->head         = new \stdClass();
        //        $table->head->fields = array(
        //            'ID',
        //            'Name',
        //            'colX',
        //            'colY',
        //        );
        //        $table->head->widths = array(
        //            '5%',
        //            null,
        //            '50px',
        //            '1em',
        //        );

        $entries = array();

        return $this->render('ElektraThemeBundle::layout.html.twig', array('table' => $table));
    }
}