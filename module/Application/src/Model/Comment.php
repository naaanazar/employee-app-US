<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Contract
 * @package Application\Model
 * @ORM\Entity(repositoryClass="Application\Model\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment
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
     * @ORM\Column(type="string")
     */
    private $body;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @var bool
     *
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $edited = false;

    /**
     * @var \Datetime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @var \Datetime
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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return mixed
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Employee $employee
     */
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * @param mixed $edited
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
    }

    /**
     * @param \Datetime $created
     */
    public function setCreated(\Datetime $created)
    {
        $this->created = $created;
    }

    /**
     * @param \Datetime $updated
     */
    public function setUpdated(\Datetime $updated)
    {
        $this->updated = $updated;
    }

}