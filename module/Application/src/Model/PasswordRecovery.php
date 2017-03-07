<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PasswordRecovery
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="password_recovery")
 */
class PasswordRecovery
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $hash;

    /**
     * @var string
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @param string $active
     */
    public function setActive(string $active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public static function generateHash()
    {
        return sha1(uniqid(static::class));
    }
}