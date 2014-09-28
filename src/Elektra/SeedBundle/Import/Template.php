<?php

namespace Elektra\SeedBundle\Import;

abstract class Template
{

    /**
     * @var Type
     */
    protected $type;

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {

        $this->type = $type;
    }

    /**
     * @return \Elektra\SeedBundle\Import\Type
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * @return string
     */
    public function getLink()
    {

        return $this->getType()->getCrud()->getNavigator()->getLinkFromRoute('imports.template.download', array('identifier' => $this->getType()->getIdentifier(), 'format' => 'xlsx'));
    }

    /**
     * @return array
     */
    public abstract function getInformation();

    /**
     * @param string $format
     *
     * @return string
     * @throws ImportException
     */
    public function create($format)
    {

        if ($format !== 'xlsx') {
            throw new ImportException('Unsupported file format: "' . $format . '"');
        }

        $path = dirname($this->getType()->getCrud()->getService('kernel')->getRootDir()) . '/var/templates/tmp.' . $format;

        $excel = new \PHPExcel();
        $excel->getProperties()->setCreator('CESU Database');
        $excel->getProperties()->setTitle($this->getType()->getTitle());
        $excel->getProperties()->setCustomProperty(Type::getCustomPropertyName(), $this->getType()->getIdentifier());

        $sheet = $excel->setActiveSheetIndex(0);
        $sheet->setTitle($this->getType()->getTitle());

        $counter = 0;
        foreach ($this->getType()->getFields() as $field) {
            $sheet->setCellValueByColumnAndRow($counter, 1, $field->name);
            $sheet->getColumnDimensionByColumn($counter)->setAutoSize(true);
            $counter++;
        }

        $writer = new \PHPExcel_Writer_Excel2007($excel);
        $writer->save($path);

        $content = file_get_contents($path);

        unlink($path);

        return $content;
    }
}