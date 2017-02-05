<?php

namespace Application\Back\Form;

use Zend\Form\Element\Email;
use Zend\Form\Form;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class Employee extends Form
{

    public function __construct(array $options)
    {
        parent::__construct(null, $options);

        $this->add(
            [
                'type' => Email::class,
                'name' => 'email'
            ]
        );

        // Todo: actualize with model
    }

}