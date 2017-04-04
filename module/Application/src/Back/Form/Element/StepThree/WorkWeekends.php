<?php

namespace Application\Back\Form\Element\StepThree;

use Application\Back\Form\Validator\InArrayValidator;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox;


/**
 * Class Rating
 * @package Application\Back\Form\Element\StepThree
 */
class WorkWeekends extends Checkbox
{
    protected function getValidator()
    {
        $this->validator = new InArrayValidator([
            'haystack' => ['Every other', 'No', 'All' ],
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