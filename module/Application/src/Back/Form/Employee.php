<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Name;
use Application\Back\Form\Element\Employee\TextRequired;
use Application\Back\Form\Element\Employee\ZIP;
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
                'type' => Name::class,
                'name' => 'surname'
            ]
        );

        $this->add(
            [
                'type' => TextRequired::class,
                'name' => 'city'
            ]
        );

        $this->add(
            [
                'type' => TextRequired::class,
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
                'type' => TextRequired::class,
                'name' => 'mobile_phone'
            ]
        );

        $this->add(
            [
                'type' => Text::class,
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
                'type' => Text::class,
                'name' => 'area_around'
            ]
        );

        $this->add(
            [
                'type' => Text::class,
                'name' => 'contract_type'
            ]
        );

        $this->add(
            [
                'type' => Text::class,
                'name' => 'weekly_hours'
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
                'type' => Text::class,
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
                'type' => Text::class,
                'name' => 'experience'
            ]
        );

        $this->add(
            [
                'type' => Checkbox::class,
                'name' => 'driving_license'
            ]
        );

        $this->add(
            [
                'type' => Checkbox::class,
                'name' => 'car_available'
            ]
        );
    }

}