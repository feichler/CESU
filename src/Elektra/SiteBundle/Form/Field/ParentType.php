<?php

namespace Elektra\SiteBundle\Form\Field;

use Symfony\Component\Form\AbstractType;

class ParentType extends AbstractType
{

    public function getParent()
    {

        return 'entity';
    }

    public function getName()
    {

        return 'parent';
    }
}