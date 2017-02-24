<?php

namespace Application\Back\Form\Element\Employee;

use Application\Back\Form\Validator\Coordinate;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class Latitude extends Element implements InputProviderInterface
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
                new Coordinate(
                    [
                        'type' => Coordinate::TYPE_LATITUDE
                    ]
                )
            ],
        ];
    }

}