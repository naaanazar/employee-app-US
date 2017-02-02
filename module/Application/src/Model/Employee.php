<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\Mvc\MvcEvent;

/**
 * Class Employee
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employee
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
     * @ORM\Column(name="address_line", length=1023, type="string", nullable=true)
     */
    private $addressLine;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="full_name", length=511, type="string", nullable=true)
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(name="mobile_phone", length=215, type="string", nullable=true)
     */
    private $mobilePhone;

    /**
     * @var string
     * @ORM\Column(name="landline_phone", length=215, type="string", nullable=true)
     */
    private $landlinePhone;

    /**
     * @var string
     * @ORM\Column(name="email", length=511, type="string", nullable=true)
     */
    private $email;

    /**
     * @var boolean
     * @ORM\Column(name="experience", type="boolean")
     */
    private $experience;

    /**
     * @var int
     * @ORM\Column(name="area_around", length=10, type="integer",  nullable=true)
     */
    private $areaAround;

    /**
     * @var boolean
     * @ORM\Column(name="driving_licence", type="boolean")
     */
    private $drivingLicence;

    /**
     * @var boolean
     * @ORM\Column(name="car_available", type="boolean")
     */
    private $carAvailable;

    /**
     * @var string
     * @ORM\Column(name="contract_type", length=511, type="string", nullable=true)
     */
    private $contractType;

    /**
     * @var int
     * @ORM\Column(name="weekly_hours_available", length=10, type="integer",  nullable=true)
     */
    private $weeklyHoursAvailable;

    /**
     * @var /Datetime
     * @ORM\Column(name="startDate", type="datetime",  nullable=true)
     */
    private $startDate;

    /**
     * @var string
     * @ORM\Column(name="comments", type="text",  nullable=true)
     */
    private $comments;

    /**
     * @var
     * @ORM\Column(name="hourly_rate", scale=5, precision=2, type="decimal",  nullable=true)
     */
    private $hourlyRate;

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
    public function getAddressLine()
    {
        return $this->addressLine;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @return string
     */
    public function getLandlinePhone()
    {
        return $this->landlinePhone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function isExperience()
    {
        return $this->experience;
    }

    /**
     * @return int
     */
    public function getAreaAround()
    {
        return $this->areaAround;
    }

    /**
     * @return boolean
     */
    public function isDrivingLicence()
    {
        return $this->drivingLicence;
    }

    /**
     * @return boolean
     */
    public function isCarAvailable()
    {
        return $this->carAvailable;
    }

    /**
     * @return string
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * @return int
     */
    public function getWeeklyHoursAvailable()
    {
        return $this->weeklyHoursAvailable;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    /**
     * @param string $addressLine
     */
    public function setAddressLine($addressLine)
    {
        $this->addressLine = $addressLine;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @param string $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @param string $landlinePhone
     */
    public function setLandlinePhone($landlinePhone)
    {
        $this->landlinePhone = $landlinePhone;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param boolean $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @param int $areaAround
     */
    public function setAreaAround($areaAround)
    {
        $this->areaAround = $areaAround;
    }

    /**
     * @param boolean $drivingLicence
     */
    public function setDrivingLicence($drivingLicence)
    {
        $this->drivingLicence = $drivingLicence;
    }

    /**
     * @param boolean $carAvailable
     */
    public function setCarAvailable($carAvailable)
    {
        $this->carAvailable = $carAvailable;
    }

    /**
     * @param string $contractType
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;
    }

    /**
     * @param int $weeklyHoursAvailable
     */
    public function setWeeklyHoursAvailable($weeklyHoursAvailable)
    {
        $this->weeklyHoursAvailable = $weeklyHoursAvailable;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param mixed $hourlyRate
     */
    public function setHourlyRate($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;
    }

}