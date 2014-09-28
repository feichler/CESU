<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\SeedBundle\Import\ImportException;
use Elektra\SeedBundle\Import\Template\SeedUnit;
use Elektra\SeedBundle\Import\Type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;

class TemplateController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Imports', 'Template');
    }

    public function browseAction($page)
    {

        $this->initialise('browse');
        $types = Type::getKnownTypes();

        $templates = array();
        foreach ($types as $type) {
            $templates[] = new $type($this->getCrud());
        }

        // get the view name (specific or common)
        $viewName = $this->getCrud()->getView('browse');

        // render the browse-view with the table
        return $this->render($viewName, array('templates' => $templates));
    }

    public function downloadAction($identifier, $format)
    {

        $this->initialise('download');

        $types = Type::getKnownTypes();

        $instance = null;
        foreach ($types as $type) {
            $typeInstance = new $type($this->getCrud());
            if ($typeInstance->getIdentifier() == $identifier) {
                $instance = $typeInstance;
                break;
            }
        }

        if ($instance === null) {
            throw new ImportException('Unknown template type found: "' . $identifier . '"');
        }

        $templateContent = $instance->getTemplate()->create($format);

        $date       = new \DateTime('now UTC');
        $dateString = $date->format('Y-m-d_H-i-s');
        $filename   = 'template_' . $identifier . '_' . $dateString . '.' . $format;

        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->setContent($templateContent);

        return $response;
    }
}