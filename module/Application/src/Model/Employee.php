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
     * @ORM\Column(name="experience", length=1, type="boolean") // ?
     */
    private $experience;

    /**
     * @var int
     * @ORM\Column(name="area_around", length=10 type="integer",  nullable=true)
     */
    private $areaAround;

    /**
     * @var boolean
     * @ORM\Column(name="driving_licence", length=1, type="boolean") // ?
     */
    private $drivingLicence;

    /**
     * @var boolean
     * @ORM\Column(name="car_available", length=1, type="boolean") // ?
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
     * @var date
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
     * @ORM\Column(name="hourly_rate", precision=2, type="decimal",  nullable=true)
     */
    private $hourlyRate;

}