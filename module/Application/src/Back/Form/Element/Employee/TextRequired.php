<?php

namespace Application\Back\Form\Element\Employee;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class TextRequired extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getInputSpecification()
    {

        $validator = new StringLength(
            [
                'min' => 2,
                'max' => 48
            ]
        );

       $validator->setMessages( array(
            StringLength::TOO_SHORT =>
                'The string \'%value%\' is too short',
            StringLength::TOO_LONG  =>
                'The string \'%value%\' is too long'
        ));

        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => [
                $validator
            ],
        ];
    }

}