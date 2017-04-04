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
use Application\Back\Form\Element\StepFive\YesNo;
use Application\Back\Form\Element\StepOne\Rating;
use Zend\Form\Element\Number;
use Zend\Filter\File\RenameUpload;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class StepFive extends Form
{

    public function __construct(array $options)
    {
        parent::__construct(null, $options);

        $this->add(
            [
                'type' => Number::class,
                'name' => 'hourly_rate',
                'attributes' => [
                    'step' => 'any'
                ]
            ]
        );

        $this->add(
            [
                'type' => YesNo::class,
                'name' => 'delinquent_or_waived'
            ]
        );

        $this->add(
            [
                'type' => YesNo::class,
                'name' => 'criminal_background'
            ]
        );



    }


}