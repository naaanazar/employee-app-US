<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class WeeklyHours
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="weekly_hours")
 */
class WeeklyHours
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
     * @ORM\Column(name="weekly_hours", length=1023, type="string", nullable=true)
     */
    private $value;

    /**
     * @var int
     * @ORM\Column(name="weekly_time_seconds", length=8, type="integer",  nullable=true)
     */
    private $ntValue;

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
    public function getNtValue()
    {
        return $this->ntValue;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param int $ntValue
     * @return $this
     */
    public function setNtValue($ntValue)
    {
        $this->ntValue = $ntValue;

        return $this;
    }

}