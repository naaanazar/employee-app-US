<?php

namespace Application\Back\Form\Element\Employee;

use Application\Back\Form\Validator\Employee\Coordinate;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class Longitude extends Element implements InputProviderInterface
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
                        'type' => Coordinate::TYPE_LONGITUDE
                    ]
                )
            ],
        ];
    }

}