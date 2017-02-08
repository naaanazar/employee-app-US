<?php

namespace Application\Back\Form\Element\Employee;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Application\Back\Form\Validator\InArrayValidator;


/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class Experience extends Element implements InputProviderInterface
{


    /**
     * Get validator
     *
     * @return array
     */
    protected function getValidators()
    {
        $validators = [];

        $validators[] = new InArrayValidator([
            'haystack' => [1, 0],
            'strict'   => false
        ]);

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