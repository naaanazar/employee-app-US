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
use Zend\Filter\File\RenameUpload;
use Zend\Form\Element\File;
use Application\Model\Employee as EmployeeModel;
use Zend\Form\Element\Number;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class Employee extends Form
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
                'type' => SourceApplication::class,
                'name' => 'source',
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
                'type' => StartDay::class,
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
                'type' => Number::class,
                'name' => 'hourly_rate',
                'attributes' => [
                    'step' => 'any'
                ]
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

        $this->add(
            [
                'type'  => File::class,
                'name'  => 'image',
            ]
        );

        $this->addInputFilter();
    }

    /**
     * Add input filter
     */
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        $hash = \Application\Model\Employee::hashKey();
        $image = 'img/employee/' . $hash . '.png';
        $this->setOption('image', $image);

        $inputFilter->add
        (
            [
                'type'     => 'Zend\InputFilter\FileInput',
                'name'     => 'image',
                'required' => false,
                'filters'  => [
                    [
                        'name'    => RenameUpload::class,
                        'options' => [
                            'target'            => BASE_PATH . DIRECTORY_SEPARATOR . $image,
                            'overwrite'         => true,
                            'randomize'         => false
                        ]
                    ]
                ],
            ]
        );

        $this->setInputFilter($inputFilter);
    }
    
}