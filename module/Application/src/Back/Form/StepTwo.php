<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Email;
use Application\Back\Form\Element\Employee\{
    Latitude,
    Longitude,
    Name,
    Surname,
    City,
    Address,
    MobileNumber,
    LandlineNumber,
    ZIP
   };

use Application\Model\Employee as EmployeeModel;
use Zend\Form\Form;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class StepTwo extends Form
{

    public function __construct(array $options)
    {
        parent::__construct('employee', $options);

        $this->add(
            [
                'type' => Name::class,
                'name' => 'name'
            ]
        );

        $this->add(
            [
                'type' => Surname::class,
                'name' => 'surname'
            ]
        );

        $this->add(
            [
                'type' => City::class,
                'name' => 'city'
            ]
        );

        $this->add(
            [
                'type' => Address::class,
                'name' => 'address'
            ]
        );

        $this->add(
            [
                'type' => Address::class,
                'name' => 'state'
            ]
        );

        $this->add(
            [
                'type' => Address::class,
                'name' => 'address_two'
            ]
        );

        $this->add(
            [
                'type' => ZIP::class,
                'name' => 'zip'
            ]
        );

        $this->add(
            [
                'type' => MobileNumber::class,
                'name' => 'mobile_phone'
            ]
        );

        $this->add(
            [
                'type' => LandlineNumber::class,
                'name' => 'landline_phone'
            ]
        );

        $allowedEmails = $this->options['allowed_emails'] ?? [];

        $this->add(
            [
                'type' => Email::class,
                'name' => 'email',
                'options' => [
                    'check_database' => EmployeeModel::class,
                    'direction' => false,
                    'allowed_emails' => $allowedEmails
                ]
            ]
        );

        $this->add(
            [
                'type' => Longitude::class,
                'name' => 'longitude',
            ]
        );

        $this->add(
            [
                'type' => Latitude::class,
                'name' => 'latitude'
            ]
        );


    }
    
}