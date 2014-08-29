<?php

namespace Elektra\SiteBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ToUppercaseTransformer implements DataTransformerInterface
{

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {

        return strtoupper($value);
    }
}