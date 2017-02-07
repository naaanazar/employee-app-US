<?php

namespace Application\Back\Form\Element\Employee;

use Application\Back\Form\Validator\RegexValidator as RegValidator;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Form\Element;
use Zend\Form\Element\Tel;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class LandlineNumber extends Tel
{
    protected function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new RegValidator("/^\+?\d+(-\d+)*$/");
        }
        return $this->validator;
    }

    /**
     * Provide default input rules for this element
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }

}