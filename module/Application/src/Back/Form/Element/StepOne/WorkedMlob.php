<?php

namespace Application\Back\Form\Element\StepOne;

use Application\Back\Form\Validator\InArrayValidator;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class WorkedMlob extends Checkbox
{
    protected function getValidator()
    {
        $this->validator = new InArrayValidator([
            'haystack' => [1, 0],
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
            'required' => true,
        ];

        if ($validator = $this->getValidator()) {
            $spec['validators'] = [
                $validator,
            ];
        }

        return $spec;
    }

}