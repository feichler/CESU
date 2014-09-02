<?php

namespace Elektra\SeedBundle\Form\Notes;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoteType extends CrudForm
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

        $this->addParentField($builder, $options, $this->getCrud()->getParentDefinition(), 'noteParent', false);
        $builder->add('title', 'text', array('required' => true));
        $builder->add('text', 'textarea', array('required' => false));
    }
}