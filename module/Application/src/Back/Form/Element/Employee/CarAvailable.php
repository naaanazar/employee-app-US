<?php

namespace Application\Back\Form\Element\Employee;

use Application\Back\Form\Validator\InArrayValidator;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class CarAvailable extends Checkbox
{
    protected function getValidator()
    {
        $this->validator = new InArrayValidator([
            'haystack' => [1, null],
            'strict'   => false
        ]);

        return $this->validator;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        $spec = [
            'name' => $this->getName(),
            'required' => false,
        ];

        if ($validator = $this->getValidator()) {
            $spec['validators'] = [
                $validator,
            ];
        }

        return $spec;
    }

}