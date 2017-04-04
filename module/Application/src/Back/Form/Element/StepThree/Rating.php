<?php

namespace Application\Back\Form\Element\StepThree;

use Application\Back\Form\Validator\InArrayValidator;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox;


/**
 * Class Rating
 * @package Application\Back\Form\Element\StepThree
 */
class Rating extends Checkbox
{
    protected function getValidator()
    {
        $this->validator = new InArrayValidator([
            'haystack' => ['less than one', 2, 3, 4, 5, 6, 7, 8, 9, '10+' ],
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