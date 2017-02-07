<?php
/**
 * Created by PhpStorm.
 * User: naaanazar
 * Date: 07.02.2017
 * Time: 13:50
 */

namespace Application\Back\Form\Validator;


use Zend\Validator\Regex;

class RegexValidator extends Regex
{
    /**
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID   => "Invalid type given. String, integer or float expected",
        self::NOT_MATCH => "The input does not match against pattern ",
        self::ERROROUS  => "There was an internal error while using the pattern",
    ];
}