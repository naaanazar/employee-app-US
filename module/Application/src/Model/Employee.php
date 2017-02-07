<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

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
    private $experience = false;

    /**
     * @var int
     * @ORM\Column(name="area_around", length=10, type="integer",  nullable=true)
     */
    private $areaAround;

    /**
     * @var boolean
     * @ORM\Column(name="driving_licence", type="boolean")
     */
    private $drivingLicence = false;

    /**
     * @var boolean
     * @ORM\Column(name="car_available", type="boolean")
     */
    private $carAvailable = false;

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
     * @var float
     * @ORM\Column(name="hourly_rate", precision=5, scale=2, type="decimal",  nullable=true)
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
     * @return $this
     */
    public function setAddressLine($addressLine)
    {
        $this->addressLine = $addressLine;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param string $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @param string $mobilePhone
     * @return $this
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * @param string $landlinePhone
     * @return $this
     */
    public function setLandlinePhone($landlinePhone)
    {
        $this->landlinePhone = $landlinePhone;

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param boolean $experience
     * @return $this
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @param int $areaAround
     * @return $this
     */
    public function setAreaAround($areaAround)
    {
        $this->areaAround = $areaAround;

        return $this;
    }

    /**
     * @param boolean $drivingLicence
     * @return $this
     */
    public function setDrivingLicence($drivingLicence)
    {
        $this->drivingLicence = $drivingLicence;

        return $this;
    }

    /**
     * @param boolean $carAvailable
     * @return $this
     */
    public function setCarAvailable($carAvailable)
    {
        $this->carAvailable = $carAvailable;

        return $this;
    }

    /**
     * @param string $contractType
     * @return $this
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;

        return $this;
    }

    /**
     * @param int $weeklyHoursAvailable
     * @return $this
     */
    public function setWeeklyHoursAvailable($weeklyHoursAvailable)
    {
        $this->weeklyHoursAvailable = $weeklyHoursAvailable;

        return $this;
    }

    /**
     * @param mixed $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @param string $comments
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @param mixed $hourlyRate
     * @return $this
     */
    public function setHourlyRate($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

}