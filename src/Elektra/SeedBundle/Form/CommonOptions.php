<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 26.08.14
 * Time: 10:38
 */

namespace Elektra\SeedBundle\Form;


use Symfony\Component\Validator\Constraints\NotBlank;

class CommonOptions
{

    public static function getRequiredNotBlank()
    {
        $options = array(
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
            )
        );

        return $options;
    }

    public static function getOptional()
    {
        $options = array(
            'required' => false
        );
        return $options;
    }
}