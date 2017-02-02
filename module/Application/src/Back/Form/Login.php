<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Password;
use Zend\Form\Element\Email;
use Zend\Form\Form;

/**
 * Class Login
 * @package Application\Back\Form
 */
class Login extends Form
{

    /**
     * Login constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct(null, $options);

        $this->add(
            [
                'type' => Email::class,
                'name' => 'email',
            ]
        );

        $this->add(
            [
                'type' => Password::class,
                'name' => 'password',
                'options' => [
                    'email'         => $this->getOption('email'),
                    'entityManager' => $this->getOption('entityManager'),
                    'check'         => \Application\Back\Form\Validator\Password::CHECK_LOGIN
                ]
            ]
        );
    }

}