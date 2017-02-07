<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Area
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="areas")
 */
class Area
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @var int
     * @ORM\Column(name="int_value", length=10, type="integer",  nullable=true)
     */
    private $intValue;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getIntValue()
    {
        return $this->intValue;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param int $intValue
     * @return $this
     */
    public function setIntValue($intValue)
    {
        $this->intValue = $intValue;

        return $this;
    }

}