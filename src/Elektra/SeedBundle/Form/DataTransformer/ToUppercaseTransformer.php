<?php

namespace Elektra\SeedBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

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