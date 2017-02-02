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

}