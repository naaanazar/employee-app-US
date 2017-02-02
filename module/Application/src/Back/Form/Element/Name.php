<?php

namespace Application\Back\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class Name extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => [
                new StringLength(
                    [
                        'min' => 4,
                        'max' => 48
                    ]
                ),
            ],
        ];
    }

}