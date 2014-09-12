<?php

namespace Elektra\SeedBundle\Form\Imports;

use Elektra\CrudBundle\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeedUnitType extends Form
{

    /**
     * {@inheritdoc}
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder,$options,'common');

        $formats = array(
            'xls' => 'MS Excel 95 - 2003',
            'xlsx' => 'MS Excel 2007 onwards',
            'ods' => 'Open Office',
        );

        $text = '<ul>';
        foreach($formats as $extension => $format) {
            $text .= '<li><b>'.$extension.'</b>: '.$format.'</li>';
        }
        $text.= '</ul>';

//        $supportedFormats = 'Excel 5 (Version 95 - 2003)<br/>';

        $common->add('supportedFormats', 'display', $this->getFieldOptions('supportedFormats')->notMapped()->add('data',$text)->toArray());
        $common->add('file','file',$this->getFieldOptions('file')->required()->notBlank()->toArray());

//        $builder->add('file', 'file', array('required' => true));

        // all crud actions have the same definition - no difference between view / add / edit

        //        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        //        $builder->add('description', 'textarea', CommonOptions::getOptional());
    }
}