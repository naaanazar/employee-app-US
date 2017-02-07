<?php

namespace Application\Back\Form\Element\Employee;

use Application\Module;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class TextRequired extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getValidators()
    {
        $validators = [];

        $validator = new StringLength(
            [
                'min' => 2,
                'max' => 48
            ]
        );

        $validator->setMessages(
            [
                StringLength::TOO_SHORT => Module::translator()->translate('The \'%value%\' is too short'),
                StringLength::TOO_LONG  => Module::translator()->translate('The \'%value%\' is too long')
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