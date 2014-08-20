<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Elektra\SeedBundle\Entity\Requests\RequestStatus;
use Elektra\SeedBundle\Form\Requests\RequestType;
use Elektra\ThemeBundle\Page\Overrides\LanguageChain;
use Elektra\ThemeBundle\Page\Overrides\LanguageChoice;
use Elektra\ThemeBundle\Page\Overrides\LanguageSimple;
use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Elektra\SeedBundle\Entity\Requests\Request as SeedRequest;

/**
 * Class RequestController
 *
 * @package Elektra\SiteBundle\Controller
 *
 * @version 0.1-dev
 */
class RequestController extends Controller
{

    /**
     * @param string $action
     * @param int    $step
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
     * @param string  $step
     * @param int     $number
     *
     * @return Response
     */
    public function indexAction(Request $request, $step, $number)
    {

        $this->initialise('index', $number);

//        $seedRequest = new SeedRequest();
//        $flow        = $this->get('elektra_seed.form.flow.request');
//        $flow->bind($seedRequest);
//        //        $flow->setGenericFormOptions(
//        //            array(
//        //                'validation_groups' => array(
//        //                    'flow_request_step1',
//        //                    'flow_request_step2',
//        //                    'flow_request_step3',
//        //                )
//        //            )
//        //        );
//        $form = $flow->createForm();
//
//        if ($flow->isValid($form)) {
//
//                        $em2 = $this->getDoctrine()->getRepository('ElektraSeedBundle:Requests\RequestStatus');
//
//            switch ($flow->getCurrentStepNumber()) {
//                case 1:
//                    $numberGenerator = $this->container->get('elektra_seed.request.numberGenerator');
//
//                    $seedRequest->setRequestStatus($em2->findOneBy(array('name' => 'Create - TOC')));
//                    $seedRequest->setTocAgreedAt(time());
//                    $seedRequest->setClientIpAddress($request->getClientIp());
//                    $seedRequest->setRequestNumber($numberGenerator->generate());
//                    $em = $this->getDoctrine()->getManager();
//                    $em->persist($seedRequest);
//                    $em->flush();
//                    //                    ladybug_dump($seedRequest);
//
//                    //                    $test = clone $seedRequest;
//                    //                    echo '<pre>';
//                    //
//                    //                    $dump = print_r($test, true);
//                    //                    foreach($dump as $key => $value) {
//                    ////                        echo $key.' => ';
//                    ////                        if(is_array($value) || is_object($value)) {
//                    ////
//                    ////                        } else {
//                    ////                            echo $value;
//                    ////                        }
//                    ////
//                    ////                        echo '<br />';
//                    //                    }
//                    //                    echo '</pre>';
//                    //                    $seedRequest->setClientIpAddress($request->getClientIp());
//                    //                    $state = $em2->findOneBy(array('name' => 'Create - TOC'));
//                    //                    $seedRequest->setRequestStatus($state);
//                    //                    $seedRequest->setRequestNumber('12345');
//                    //                    $agreeTerms = $form->get('agreeTerms')->getData();
//                    //                    if ($agreeTerms) {
//                    //                        $seedRequest->setTocAgreedAt(time());
//                    //                    }
//                    //                    //                    var_dump($seedRequest);
//                    //                    $em->persist($seedRequest);
//                    //                    //                    var_dump($seedRequest);
//                    //                    $em->flush();
//
//                    //                    $agreeTerms = $form->get('agreeTerms')->getData();
//                    //                    if($agreeTerms === false) {
//                    //                        $seedRequest = new SeedRequest();
//                    //                        $flow->bind($seedRequest);
//                    //                        $this->get('session')->getFlashBag()->add('error','must agree toc');
//                    //                        $flow->reset();
//                    //
//                    ////                        $flow->invalidateStepData(1);
//                    ////                        $flow->setFormStepKey(0);
//                    ////                        $flow->
//                    //                    }
//                    //                    var_dump($agreeTerms);
//                    break;
//                case 2:
//                    break;
//                case 3:
//                    break;
//                case 4:
//                    break;
//            }
//
//            $flow->saveCurrentStepData($form);
//
//            //var_dump($flow->getCurrentStepNumber());
//            //
//            //            echo $flow->getCurrentStep();
//            //            $data = $form->getData();
//            //            echo get_class($data);
//            //            var_dump($data === $seedRequest);
//            //            echo $form->get('test1')->getData();
//
//            //            $em = $this->getDoctrine()->getManager();
//
//            if ($flow->nextStep()) {
//                $form = $flow->createForm();
//            } else {
//                // flow finished
//                $em = $this->getDoctrine()->getManager();
//                // TODO process and persist the request
//                //                $em->persist($seedRequest);
//                //                $em->flush();
//
//                $flow->reset();
//
//                // TODO request "thank-you" page
//                return new Response('THANK YOU');
//            }
//        }
//
//        //        $form = $this->createForm(new RequestType(), $seedRequest);
//
//        $view = $form->createView();
//
//        return $this->render('ElektraSiteBundle:Site:request.html.twig', array('form' => $view, 'flow' => $flow));
//
//        return $this->render('ElektraSeedBundle::base-form.html.twig', array('form' => $view, 'flow' => $flow));

        //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:Requests\Request');
        //        $ent

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