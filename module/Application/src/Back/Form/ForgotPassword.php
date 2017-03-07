<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Email;
use Application\Model\User;
use Zend\Form\Form;

/**
 * Class Login
 * @package Application\Back\Form
 */
class ForgotPassword extends Form
{

    /**
     * Login constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(null, $options);

        $this->add(
            [
                'type' => Email::class,
                'name' => 'email',
                'options' => [
                    'check_database' => User::class
                ]
            ]
        );
    }

}