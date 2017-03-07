<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Email;
use Application\Back\Form\Element\Password;
use Application\Back\Form\Element\PasswordLength;
use Application\Back\Form\Validator\StringLength;
use Application\Model\User;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class Login
 * @package Application\Back\Form
 */
class RecoverPassword extends Form
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
                'type' => PasswordLength::class,
                'name' => 'password',
            ]
        );
    }

}