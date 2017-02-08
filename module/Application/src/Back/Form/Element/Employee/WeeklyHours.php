<?php

namespace Application\Back\Form\Element\Employee;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class WeeklyHours extends Element implements InputProviderInterface
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
                new \Application\Back\Form\Validator\WeeklyHours(),
            ],
        ];
    }

}