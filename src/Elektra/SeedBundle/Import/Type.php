<?php

namespace Elektra\SeedBundle\Import;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\SeedBundle\Entity\Imports\Import;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

abstract class Type
{

    /**
     * @var string
     */
    protected static $customPropertyName = 'import_identifier';

    /**
     * @return string
     */
    public static function getCustomPropertyName()
    {

        return self::$customPropertyName;
    }

    /**
     * @return array
     */
    public static final function getKnownTypes()
    {

        $types = array();
        $path  = dirname(__FILE__) . '/Type';

        $contents = scandir($path);
        foreach ($contents as $content) {
            if ($content == '.' || $content == '..') {
                continue;
            }
            $contentPath = $path . '/' . $content;
            $info        = pathinfo($contentPath);
            if (array_key_exists('extension', $info) && $info['extension'] == 'php') {
                $fileContent = file_get_contents($contentPath);
                $tokens      = token_get_all($fileContent);
                $count       = count($tokens);
                for ($i = 2; $i < $count; $i++) {
                    if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {

                        $type       = 'Elektra\SeedBundle\Import\Type\\' . $tokens[$i][1];
                        $reflection = new \ReflectionClass($type);
                        if (!$reflection->isAbstract()) {

                            $order = forward_static_call(array($type, 'getOrder'));

                            $types[$order][] = $type;
                        }
                    }
                }
            }
        }

        $orderedTypes = array();
        ksort($types);
        foreach ($types as $typeArray) {
            foreach ($typeArray as $type) {
                $orderedTypes[] = $type;
            }
        }

        return $orderedTypes;
    }

    /**
     * @return int
     */
    public static function getOrder()
    {

        // may be overridden to change the ordering the templates appear in the UI

        return 1000;
    }

    /**
     * @var Crud
     */
    protected $crud;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @param Crud $crud
     */
    public function __construct(Crud $crud)
    {

        $this->crud = $crud;

        $this->fields = array();

        $className      = get_class($this);
        $templateClass  = str_replace('Type', 'Template', $className);
        $processorClass = str_replace('Type', 'Processor', $className);

        $this->template  = new $templateClass($this);
        $this->processor = new $processorClass($this);

        $this->initialiseFields();
    }

    /**
     * @return \Elektra\CrudBundle\Crud\Crud
     */
    public function getCrud()
    {

        return $this->crud;
    }

    /**
     * @return \Elektra\SeedBundle\Import\Template
     */
    public function getTemplate()
    {

        return $this->template;
    }

    /**
     * @return \Elektra\SeedBundle\Import\Processor
     */
    public function getProcessor()
    {

        return $this->processor;
    }

    /**
     * @return void
     */
    protected abstract function initialiseFields();

    /**
     * @param string $id
     * @param string $name
     * @param bool   $required
     * @param array  $comments
     */
    protected final function addField($id, $name, $required, $comments = array())
    {

        $field           = new \stdClass();
        $field->id       = $id;
        $field->name     = $name;
        $field->required = $required;
        $field->comments = $comments;

        $this->fields[$id] = $field;
    }

    /**
     * @return array
     */
    public function getFields()
    {

        return $this->fields;
    }

    /**
     * @return bool
     */
    public function hasDateFields()
    {

        if (array_key_exists('date', $this->fields) && array_key_exists('time', $this->fields) && array_key_exists('timezone', $this->fields)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public abstract function getIdentifier();

    /**
     * @return string
     */
    public abstract function getTitle();

    /**
     * @param Import $import
     *
     * @return bool
     * @throws ImportException
     */
    public function canHandle(Import $import)
    {

        $uploadPath = $import->getUploadFile()->getRealPath();
        $reader     = \PHPExcel_IOFactory::createReaderForFile($uploadPath);

        if (!$reader instanceof \PHPExcel_Reader_Excel2007) {
            throw new ImportException('Unsupported file format');
        }

        $excel = $reader->load($uploadPath);

        if (!$excel->getProperties()->isCustomPropertySet(static::getCustomPropertyName())) {
            throw new ImportException('File has no identifier set!');
        }

        $identifier = $excel->getProperties()->getCustomPropertyValue(static::getCustomPropertyName());

        if ($identifier == $this->getIdentifier()) {
            $this->processor->setExcel($excel);
            $this->processor->setImportEntity($import);

            return true;
        }

        return false;
    }

    protected $messages = array();

    /**
     * @param int $row
     */
    public function addMessagesNow($row)
    {

        $bag = $this->getCrud()->getService('session')->getFlashBag();
        foreach ($this->messages as $type => $messages) {
            $rowMessage = '';
            //            $rowMessage = 'Row ' . $row;
            //            $bag->add($type, $rowMessage);
            foreach ($messages as $message) {
                $rowMessage .= 'Row ' . $row . ': ' . $message.'<br />';
            }
            //            echo $rowMessage;
            $bag->add($type, $rowMessage);
        }
        $this->messages = array();
    }

    /**
     * @param string $type
     * @param string $message
     */
    protected function addMessage($type, $message)
    {

        $this->messages[$type][] = $message;

        //        $this->getCrud()->getService('session')->getFlashBag()->add($type, $message);
        //        echo $type . ': ' . $message . '<br />';
    }

    /**
     * @param string $message
     */
    public function addErrorMessage($message)
    {

        $this->addMessage('error', $message);
    }

    /**
     * @param string $message
     */
    public function addWarningMessage($message)
    {

        $this->addMessage('warning', $message);
    }

    /**
     * @param string $message
     */
    public function addSuccessMessage($message)
    {

        $this->addMessage('success', $message);
    }

    /**
     * @param string $message
     */
    public function addInfoMessage($message)
    {

        $this->addMessage('info', $message);
    }

    /**
     * @param string $type
     */
    public function removeMessages($type)
    {

        $flashBag = $this->getCrud()->getService('session')->getFlashBag();
        $flashBag->get($type); // get also unsets the specific type
    }
}