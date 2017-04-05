<?php

namespace Application\Model;

use Application\Model\AbstractModel\Concern\Constants;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Test
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="test")
 */
class Test
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
     * @var integer
     * @ORM\Column(name="net_wpm", type="integer", nullable=true)
     */
    private $netWPM;

    /**
     * @var integer
     * @ORM\Column(name="gross_wpm", type="integer", nullable=true)
     */
    private $grossWPM;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $errors;

    /**
     * @var float
     * @ORM\Column(precision=5, scale=2, type="decimal",  nullable=true)
     */
    private $accuracy;

    /**
     * @var string
     * @ORM\Column(name="years_employed", type="string", nullable=true)
     */
    private $yearsEmployed;

    /**
     * @var /Datetime
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $created;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    /**
     * @return int
     */
    public function getNetWPM(): int
    {
        return $this->netWPM;
    }

    /**
     * @return int
     */
    public function getGrossWPM(): int
    {
        return $this->grossWPM;
    }

    /**
     * @return int
     */
    public function getErrors(): int
    {
        return $this->errors;
    }

    /**
     * @return float
     */
    public function getAccuracy(): float
    {
        return $this->accuracy;
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
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

    /**
     * @param int $netWPM
     * @return $this
     */
    public function setNetWPM(int $netWPM)
    {
        $this->netWPM = $netWPM;

        return $this;
    }

    /**
     * @param int $grossWPM
     * @return $this
     */
    public function setGrossWPM(int $grossWPM)
    {
        $this->grossWPM = $grossWPM;

        return $this;
    }

    /**
     * @param int $errors
     * @return $this
     */
    public function setErrors(int $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param float $accuracy
     * @return $this
     */
    public function setAccuracy(float $accuracy)
    {
        $this->accuracy = $accuracy;

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
     * @param $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param int $time
     * @return $this
     */
    public function setTime(int $time)
    {
        $this->time = $time;

        return $this;

    }




}