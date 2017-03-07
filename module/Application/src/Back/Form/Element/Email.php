<?php

namespace Application\Back\Form\Element;

use Application\Back\Form\Validator\RegexValidator;
use Application\Back\Form\Validator\UserEmail;
use Zend\Form\Element\Email as EmailElement;

/**
 * Class Email
 * @package Application\Back\Form\Element
 */
class Email extends EmailElement
{

    /**
     * @return RegexValidator[]
     */
    public function getValidators()
    {
        $validators[] = new RegexValidator('/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/');

        if (true === isset($this->options['check_database']) && true === class_exists($entityName = $this->getOption('check_database'))) {
            $validators[] = new UserEmail(
                [
                    'entity' => $entityName
                ]
            );
        }

        return $validators;
    }

    /**
     * Provide default input rules for this element
     *
     * Attaches an email validator.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'filters' => [
                ['name' => 'Zend\Filter\StringTrim'],
            ],
            'validators' => $this->getValidators(),
        ];
    }
}