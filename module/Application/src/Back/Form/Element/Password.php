<?php

namespace Application\Back\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

/**
 * Class Password
 * @package Application\Back\Form\Element
 */
class Password extends Element implements InputProviderInterface
{

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => [
                new \Application\Back\Form\Validator\Password(
                    [
                        'email'         => $this->getOption('email'),
                        'entityManager' => $this->getOption('entityManager'),
                        'check'         => $this->getOption('check')
                    ]
                ),
                new StringLength(['min' => 6, 'max' => 48])
            ],
        ];
    }

}