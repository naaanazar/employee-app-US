<?php
/**
 * Created by PhpStorm.
 * User: naaanazar
 * Date: 07.02.2017
 * Time: 13:50
 */

namespace Application\Back\Form\Validator;

use Zend\Validator\StringLength as StrLength;

class StringLength extends StrLength
{
    /**
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID   => "Invalid type given. String expected",
        self::TOO_SHORT => "The input is less than %min% characters long11",
        self::TOO_LONG  => "The input is more than %max% characters long22",
    ];
}