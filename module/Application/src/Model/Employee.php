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
     * @ORM\Column(name="address", length=1023, type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(length=1023, type="string", nullable=true)
     */
    private $city;

    /**
     * @var int
     * @ORM\Column(length=10, type="integer",  nullable=true)
     */
    private $zip;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(length=511, type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(length=511, type="string", nullable=true)
     */
    private $surname;

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
     * @var Area
     * @ORM\ManyToOne(targetEntity="Area")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
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
     * @var Contract
     * @ORM\ManyToOne(targetEntity="Contract")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    private $contract;

    /**
     * @var WeeklyHours
     * @ORM\ManyToOne(targetEntity="WeeklyHours")
     * @ORM\JoinColumn(name="weekly_hours_id", referencedColumnName="id")
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
     * @var /Datetime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @var /Datetime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return int
     */
    public function getZip()
    {
        return $this->zip;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
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
     * @return Area
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
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @return WeeklyHours
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
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

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
     * @param Area $areaAround
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
     * @param Contract $contractType
     * @return $this
     */
    public function setContract($contractType)
    {
        $this->contract = $contractType;

        return $this;
    }

    /**
     * @param WeeklyHours $weeklyHoursAvailable
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

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param $zip
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }


}