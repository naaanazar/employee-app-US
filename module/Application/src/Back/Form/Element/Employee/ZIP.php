<?php

namespace Application\Back\Form\Element\Employee;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;
use Application\Back\Form\Validator\RegexValidator;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class ZIP extends Element implements InputProviderInterface
{
    /**
     * @var array
     */
    protected $validators;

    /**
     * Get validator
     *
     * @return \Zend\Validator\ValidatorInterface[]
     */
    protected function getValidators()
    {

        $validators = [];

        $validators[] = new RegexValidator('(^-?\d*(\.\d+)?$)');

        $validators[] = new StringLength(
            [
                'min' => 2,
                'max' => 10
            ]
        );

        $this->validators = $validators;
        return $this->validators;
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