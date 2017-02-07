<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Coordinates
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="coordinates")
 */
class Coordinates
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(precision=21, scale=18, type="decimal",  nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(precision=21, scale=18, type="decimal",  nullable=true)
     */
    private $longitude;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return Employee
     */
    public function getEmployeeId()
    {
        return $this->employee;
    }

    /**
     * @param $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @param Employee $emplotee
     * @return $this
     */
    public function setEmploteeId(Employee $emplotee)
    {
        $this->employee = $emplotee;

        return $this;
    }

}