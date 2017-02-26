<?php

namespace Application\Back\Form\Element\Employee;

use Application\Module;
use Application\Back\Form\Validator\StartDay as StartDate;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class StartDay extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getValidators()
    {
        $validators = [];

        $validator = new StartDate(
            [
                'format' => 'd-m-Y',

            ]
        );

        $validators[] = $validator;

        return $validators;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {

        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => $this->getValidators(),
        ];
    }

}