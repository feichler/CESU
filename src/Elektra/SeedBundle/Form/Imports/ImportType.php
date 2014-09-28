<?php

namespace Elektra\SeedBundle\Form\Imports;

use Elektra\CrudBundle\Form\Form;
use Elektra\SeedBundle\Import\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportType extends Form
{

    /**
     * @param OptionsResolverInterface $resolver
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Implement setSpecificDefaultOptions() method.
    }

    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $crudAction = $options['crud_action'];

        $common = $this->addFieldGroup($builder, $options, 'common');

        if ($crudAction == 'add') {
            $common->add('supportedFormats', 'display', $this->getFieldOptions('supportedFormats')->notMapped()->add('data', 'xlsx - Excel 2007 and onwards')->toArray());
            $common->add('uploadFile', 'file', $this->getFieldOptions('uploadFile')->required()->notBlank()->toArray());
        }

        if ($crudAction == 'view') {
            $common->add('originalFileName', 'text', $this->getFieldOptions('originalFileName')->toArray());
            $common->add('serverFileName', 'text', $this->getFieldOptions('serverFileName')->toArray());
            $common->add('importType', 'text', $this->getFieldOptions('importType')->toArray());
            $common->add('numberOfEntries', 'text', $this->getFieldOptions('numberOfEntries')->toArray());
//            $common->add('numberOfEntriesProcessed', 'text', $this->getFieldOptions('numberOfEntriesProcessed')->toArray());
        }
    }
}
