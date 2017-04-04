<?php

namespace Application\Back\Form;

use Application\Back\Form\Element\StepOne\WorkedMlob;
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
                'type' => WorkedMlob::class,
                'name' => 'worked_mlob'
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