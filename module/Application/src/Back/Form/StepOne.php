<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\StepOne\Rating;
use Application\Back\Form\Element\Employee\Name;
use Application\Back\Form\Element\Employee\AreaAround;
use Zend\Filter\File\RenameUpload;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Class Employee
 * @package Application\Back\Form
 */
class StepOne extends Form
{

    public function __construct(array $options)
    {
        parent::__construct(null, $options);

        $this->add(
            [
                'type' => Name::class,
                'name' => 'position_applying'
            ]
        );

        $this->add(
            [
                'type' => Name::class,
                'name' => 'location'
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
                'type' => Rating::class,
                'name' => 'worked_mlob'
            ]
        );


    }


}