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
        self::INVALID   => /*translate*/'Invalid type given. String expected'/*translate*/,
        self::TOO_SHORT => /*translate*/'The input is less than %min% characters long'/*translate*/,
        self::TOO_LONG  => /*translate*/'The input is more than %max% characters long'/*translate*/,
    ];
}