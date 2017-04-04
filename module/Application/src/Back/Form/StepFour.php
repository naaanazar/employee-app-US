<?php

namespace Application\Back\Form;
use Application\Back\Form\Element\Employee\{
    Latitude,
    Longitude,
    Name,
    Surname,
    City,
    Address,
    MobileNumber,
    LandlineNumber,
    ZIP,
    DrivingLicence,
    CarAvailable,
    Comments,
    Experience,
    AreaAround,
    ContractType,
    WeeklyHours,
    StartDay,
    SourceApplication
};
use Application\Back\Form\Element\StepOne\Rating;
use Zend\Filter\File\RenameUpload;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class StepFour extends Form
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
                'type' => City::class,
                'name' => 'city'
            ]
        );

        $this->add(
            [
                'type' => City::class,
                'name' => 'state'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'years_employed'
            ]
        );

        $this->add(
            [
                'type' => StartDay::class,
                'name' => 'start'
            ]
        );

        $this->add(
            [
                'type' => StartDay::class,
                'name' => 'end'
            ]
        );

        $this->add(
            [
                'type' => Comments::class,
                'name' => 'comments'
            ]
        );

    }


}