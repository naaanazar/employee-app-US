<?php

namespace Application\Back\Form\Element\Employee;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class SourceApplication
 * @package Application\Back\Form\Element
 */
class SourceApplication extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'validators' => [
                new \Application\Back\Form\Validator\SourceApplication(),
            ],
        ];
    }

}