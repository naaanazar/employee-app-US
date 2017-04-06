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
use Application\Back\Form\Element\StepThree\Rating;
use Zend\Form\Element\Number;
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
                'name' => 'name_ex'
            ]
        );

        $this->add(
            [
                'type' => City::class,
                'name' => 'city_ex'
            ]
        );

        $this->add(
            [
                'type' => City::class,
                'name' => 'state_ex'
            ]
        );

        $this->add(
            [
                'type' => Rating::class,
                'name' => 'years_employed_ex'
            ]
        );

        $this->add(
            [
                'type' => Number::class,
                'name' => 'start_ex',
                'attributes' => [
                    'step' => 'any'
                ]
            ]
        );

        $this->add(
            [
                'type' => Number::class,
                'name' => 'end_ex',
                'attributes' => [
                    'step' => 'any'
                ]
            ]
        );

        $this->add(
            [
                'type' => Comments::class,
                'name' => 'comments_ex'
            ]
        );

    }


}