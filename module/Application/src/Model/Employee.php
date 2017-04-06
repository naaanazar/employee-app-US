<?php

namespace Application\Model;

use Application\Model\AbstractModel\ArraySerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Employee
 * @package Application\Model
 * @ORM\Entity(repositoryClass="Application\Model\Repository\EmployeeRepository")
 * @ORM\Table(name="employees")
 */
class Employee extends ArraySerializable
{

    const EXPIRIENCE_SELECT = ['less than one', '2', '3', '4', '5','6', '7', '8', '9', '10+'];

    const POSITION_APPLING_SELECT  = [
        'Part-Time Clerk',
        'Full-Time Clerk',
        'Traveling Clerk',
        'Assistant Manager',
        'Office Manager',
        'Contract Manager',
        'Regional Manager'
    ];

    const LOCATION_SELECT  = [
        'Branson West',
        'Carthage',
        'Cassville',
        'Clayton',
        'Clinton',
        'Creve Coeur',
        'Des Peres',
        'Eldon',
        'Imperial',
        'Lees Summit',
        'Marshfield',
        'Nevada',
        'Poplar Bluff',
        'Rolla',
        'Springfield',
        'Warrensburg',
        'Union'
    ];

    const STATES_SELECT = [
        "Alaska",
        "Alabama",
        "Arkansas",
        "American Samoa",
        "Arizona",
        "California",
        "Colorado",
        "Connecticut",
        "District of Columbia",
        "Delaware",
        "Florida",
        "Georgia",
        "Guam",
        "Hawaii",
        "Iowa",
        "Idaho",
        "Illinois",
        "Indiana",
        "Kansas",
        "Kentucky",
        "Louisiana",
        "Massachusetts",
        "Maryland",
        "Maine",
        "Michigan",
        "Minnesota",
        "Missouri",
        "Mississippi",
        "Montana",
        "North Carolina",
        " North Dakota",
        "Nebraska",
        "New Hampshire",
        "New Jersey",
        "New Mexico",
        "Nevada",
        "New York",
        "Ohio",
        "Oklahoma",
        "Oregon",
        "Pennsylvania",
        "Puerto Rico",
        "Rhode Island",
        "South Carolina",
        "South Dakota",
        "Tennessee",
        "Texas",
        "Utah",
        "Virginia",
        "Virgin Islands",
        "Vermont",
        "Washington",
        "Wisconsin",
        "West Virginia",
        "Wyoming"
    ];

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
     * @var string
     * @ORM\Column(length=255, type="string", nullable=true)
     */
    private $zip;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @var ReasonRemoval
     * @ORM\ManyToOne(targetEntity="ReasonRemoval")
     * @ORM\JoinColumn(name="reason_removal_id", referencedColumnName="id")
     */
    private $reasonRemoval;

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
     * @var SourceApplication
     * @ORM\ManyToOne(targetEntity="SourceApplication")
     * @ORM\JoinColumn(name="source_application_id", referencedColumnName="id")
     */
    private $sourceApplication;

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
     * @var string
     * @ORM\Column(type="text")
     */
    private $hash;

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(name="job_status", length=215, type="string")
     */
    private $jobStatus;

    /**
     * @var string
     * @ORM\Column(name="position_applying", length=1023, type="string", nullable=true)
     */
    private $positionApplying;

    /**
     * @var string
     * @ORM\Column(length=1023, type="string", nullable=true)
     */
    private $location;

    /**
     * @var boolean
     * @ORM\Column(name="worked_mlob", type="boolean")
     */
    private $workedMlob = false;

    /**
     * @var string
     * @ORM\Column(name="address_two", length=1023, type="string", nullable=true)
     */
    private $addressTwo;

    /**
     * @var string
     * @ORM\Column(length=255, type="string", nullable=true)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="work_weekends", length=255, type="string", nullable=true)
     */
    private $workWeekends;

    /**
     * @var string
     * @ORM\Column(name="customer_service_expierence", length=255, type="string", nullable=true)
     */
    private $customerServiceExpierence;

    /**
     * @var string
     * @ORM\Column(name="business_operations_expierence", length=255, type="string", nullable=true)
     */
    private $businessOperationsExpierence;

    /**
     * @var string
     * @ORM\Column(name="management_expierence", length=255, type="string", nullable=true)
     */
    private $managementExpierence;

    /**
     * @var string
     * @ORM\Column(name="expierence_word", length=255, type="string", nullable=true)
     */
    private $expierenceWord;

    /**
     * @var string
     * @ORM\Column(name="expierence_exel", length=255, type="string", nullable=true)
     */
    private $expierenceExel;

    /**
     * @var string
     * @ORM\Column(name="expierence_keypad", length=255, type="string", nullable=true)
     */
    private $expierenceKeypad;

    /**
     * @var boolean
     * @ORM\Column(name="delinquent_or_waived", type="boolean")
     */
    private $delinquentOrWaived = false;

    /**
     * @var boolean
     * @ORM\Column(name="criminal_background", type="boolean")
     */
    private $criminalBackground = false;

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
     * @return string/null
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
     * @return ReasonRemoval
     */
    public function getReasonRemoval(): ReasonRemoval
    {
        return $this->reasonRemoval;
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
     * @return SourceApplication
     */
    public function getSourceApplication()
    {
        return $this->sourceApplication;
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
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**]
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    /**
     * @return string|null
     */
    public function getJobStatus()
    {
        return $this->jobStatus;
    }

    /**
     * @return string
     */
    public function getPositionApplying(): string
    {
        return $this->positionApplying;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return bool
     */
    public function isWorkedMlob(): bool
    {
        return $this->workedMlob;
    }

    /**
     * @return string
     */
    public function getAddressTwo(): string
    {
        return $this->addressTwo;
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
    public function getWorkWeekends(): string
    {
        return $this->workWeekends;
    }

    /**
     * @return string
     */
    public function getCustomerServiceExpierence(): string
    {
        return $this->customerServiceExpierence;
    }

    /**
     * @return string
     */
    public function getBusinessOperationsExpierence(): string
    {
        return $this->businessOperationsExpierence;
    }

    /**
     * @return string
     */
    public function getManagementExpierence(): string
    {
        return $this->managementExpierence;
    }

    /**
     * @return string
     */
    public function getExpierenceWord(): string
    {
        return $this->expierenceWord;
    }

    /**
     * @return string
     */
    public function getExpierenceExel(): string
    {
        return $this->expierenceExel;
    }

    /**
     * @return string
     */
    public function getExpierenceKeypad(): string
    {
        return $this->expierenceKeypad;
    }

    /**
     * @return bool
     */
    public function isDelinquentOrWaived(): bool
    {
        return $this->delinquentOrWaived;
    }

    /**
     * @return bool
     */
    public function isCriminalBackground(): bool
    {
        return $this->criminalBackground;
    }

    /**
     * @param string $jobStatus
     * @return $this
     */
    public function setJobStatus(string $jobStatus)
    {
        $this->jobStatus = $jobStatus;

        return $this;
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
     * @param SourceApplication $sourceApplication
     * @return $this
     */
    public function setSourceApplication($sourceApplication)
    {
        $this->sourceApplication = $sourceApplication;
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
     * @param $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param ReasonRemoval $reasonRemoval
     * @return $this
     */
    public function setReasonRemoval($reasonRemoval)
    {
        $this->reasonRemoval = $reasonRemoval;

        return $this;
    }

    /**
     * @param $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @param $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @param string $positionApplying
     * @return $this
     */
    public function setPositionApplying(string $positionApplying)
    {
        $this->positionApplying = $positionApplying;

        return $this;
    }

    /**
     * @param string $location
     * @return $this
     */
    public function setLocation(string $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @param bool $workedMlob
     * @return $this
     */
    public function setWorkedMlob(bool $workedMlob)
    {
        $this->workedMlob = $workedMlob;

        return $this;
    }

    /**
     * @param string $addressTwo
     * @return $this
     */
    public function setAddressTwo(string $addressTwo)
    {
        $this->addressTwo = $addressTwo;

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
     * @param string $workWeekends
     * @return $this
     */
    public function setWorkWeekends(string $workWeekends)
    {
        $this->workWeekends = $workWeekends;

        return $this;
    }

    /**
     * @param string $customerServiceExpierence
     * @return $this
     */
    public function setCustomerServiceExpierence(string $customerServiceExpierence)
    {
        $this->customerServiceExpierence = $customerServiceExpierence;

        return $this;
    }

    /**
     * @param string $businessOperationsExpierence
     * @return $this
     */
    public function setBusinessOperationsExpierence(string $businessOperationsExpierence)
    {
        $this->businessOperationsExpierence = $businessOperationsExpierence;

        return $this;
    }

    /**
     * @param string $managementExpierence
     * @return $this
     */
    public function setManagementExpierence(string $managementExpierence)
    {
        $this->managementExpierence = $managementExpierence;

        return $this;
    }

    /**
     * @param string $expierenceWord
     * @return $this
     */
    public function setExpierenceWord(string $expierenceWord)
    {
        $this->expierenceWord = $expierenceWord;

        return $this;
    }

    /**
     * @param string $expierenceExel
     * @return $this
     */
    public function setExpierenceExel(string $expierenceExel)
    {
        $this->expierenceExel = $expierenceExel;

        return $this;
    }

    /**
     * @param string $expierenceKeypad
     * @return $this
     */
    public function setExpierenceKeypad(string $expierenceKeypad)
    {
        $this->expierenceKeypad = $expierenceKeypad;

        return $this;
    }

    /**
     * @param bool $delinquentOrWaived
     * @return $this
     */
    public function setDelinquentOrWaived(bool $delinquentOrWaived)
    {
        $this->delinquentOrWaived = $delinquentOrWaived;

        return $this;
    }

    /**
     * @param bool $criminalBackground
     * @return $this
     */
    public function setCriminalBackground(bool $criminalBackground)
    {
        $this->criminalBackground = $criminalBackground;

        return $this;
    }

    /**
     * @return string
     */
    public static function hashKey()
    {
        return sha1(uniqid(static::class));
    }

}