<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\Email;
use Application\Back\Form\Element\Employee\{
    Name,
    WeeklyHours,
    StartDay
};

use Application\Back\Form\Element\StepThree\Rating;
use Application\Back\Form\Element\StepThree\WorkWeekends;
use Application\Model\Employee as EmployeeModel;
use Zend\Form\Form;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class StepThree extends Form
{

    public function __construct(array $options)
    {
        parent::__construct('employee', $options);

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
                'type' => StartDay::class,
                'name' => 'start_date'
            ]
        );

        $this->add(
            [
                'type' => WorkWeekends::class,
                'name' => 'work_weekends'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'customer_service_expierence'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'business_operations_expierence'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'management_expierence'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'expierence_word'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'expierence_exel'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'expierence_keypad'
            ]
        );



    }
    
}