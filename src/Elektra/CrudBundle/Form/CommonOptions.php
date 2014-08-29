<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 26.08.14
 * Time: 10:38
 */

namespace Elektra\CrudBundle\Form;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommonOptions
{

    public static function getRequiredNotBlank()
    {
        $options = array(
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required'))
            )
        );

        return $options;
    }

    public static function getUniqueName()
    {
        $options = array(
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
                new UniqueEntity(array(
                    'fields' => 'name',
                    'message' => 'test!'
                ))
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