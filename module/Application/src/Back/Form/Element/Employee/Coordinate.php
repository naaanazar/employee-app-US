<?php

namespace Application\Back\Form\Element\Employee;
use Zend\Validator\AbstractValidator;

/**
 * Class Coordinate
 * @package Application\Back\Form\Element\Employee
 */
class Coordinate extends AbstractValidator
{

    /**
     * Type constants with maximum values
     */
    const TYPE_LATITUDE  = 90;
    const TYPE_LONGITUDE = 180;

    /**
     * Coordinate constructor.
     * @param array|null|\Traversable $options
     */
    public function __construct($options)
    {
        if (false === isset($options['type'])) {
            $options['type'] = 0;
        }

        parent::__construct($options);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = false;

        if (is_float($value) && abs($value) < $this->getOption('type')) {
            $result = true;
        }

        return $result;
    }

}