<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Employee\Name;
use Application\Back\Form\Element\Employee\Surname;
use Application\Back\Form\Element\Employee\City;
use Application\Back\Form\Element\Employee\Address;
use Application\Back\Form\Element\Employee\MobileNumber;
use Application\Back\Form\Element\Employee\LandlineNumber;
use Application\Back\Form\Element\Employee\ZIP;
use Application\Back\Form\Element\Employee\DrivingLicence;
use Application\Back\Form\Element\Employee\CarAvailable;
use Application\Back\Form\Element\Employee\Comments;
use Application\Back\Form\Element\Employee\Experience;
use Application\Back\Form\Element\Employee\AreaAround;
use Application\Back\Form\Element\Employee\ContractType;
use Application\Back\Form\Element\Employee\WeeklyHours;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Email;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
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

        $this->add(
            [
                'type' => Email::class,
                'name' => 'email'
            ]
        );

        $this->add(
            [
                'type' => AreaAround::class,
                'name' => 'area_around',
                'options' => [
                    'entityManager' => $this->getOption('entityManager'),
                ]
            ]
        );

        $this->add(
            [
                'type' => ContractType::class,
                'name' => 'contract_type',
                'options' => [
                    'entityManager' => $this->getOption('entityManager'),
                ]
            ]
        );

        $this->add(
            [
                'type' => WeeklyHours::class,
                'name' => 'weekly_hours',
                'options' => [
                    'entityManager' => $this->getOption('entityManager'),
                ]
            ]
        );

        $this->add(
            [
                'type' => Text::class,
                'name' => 'start_date'
            ]
        );

        $this->add(
            [
                'type' => Comments::class,
                'name' => 'comments'
            ]
        );

        $this->add(
            [
                'type' => Text::class,
                'name' => 'hourly_rate'
            ]
        );

        $this->add(
            [
                'type' => Experience::class,
                'name' => 'experience'
            ]
        );

        $this->add(
            [
                'type' => DrivingLicence::class,
                'name' => 'driving_license'
            ]
        );

        $this->add(
            [
                'type' => CarAvailable::class,
                'name' => 'car_available'
            ]
        );
    }

}