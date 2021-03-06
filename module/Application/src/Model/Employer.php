<?php

namespace Application\Model;

use Application\Model\AbstractModel\Concern\Constants;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Employer
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="employer")
 */
class Employer
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(length=1023, type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(length=1023, type="string", nullable=true)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="years_employed", type="string", nullable=true)
     */
    private $yearsEmployed;

    /**
     * @var float
     * @ORM\Column(precision=5, scale=2, type="decimal",  nullable=true)
     */
    private $start;

    /**
     * @var float
     * @ORM\Column(precision=5, scale=2, type="decimal",  nullable=true)
     */
    private $end;

    /**
     * @var string
     * @ORM\Column(type="text",  nullable=true)
     */
    private $comments;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getYearsEmployed(): string
    {
        return $this->yearsEmployed;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getComments(): string
    {
        return $this->comments;
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $yearsEmployed
     * @return $this
     */
    public function setYearsEmployed(string $yearsEmployed)
    {
        $this->yearsEmployed = $yearsEmployed;

        return $this;
    }

    /**
     * @param $start
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @param $end
     * @return $this
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @param string $comments
     * @return $this
     */
    public function setComments(string $comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @param Employee $employee
     * @return $this
     */
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;

        return $this;
    }

}