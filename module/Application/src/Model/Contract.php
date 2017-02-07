<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Contract
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="contracts")
 */
class Contract
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
     * @ORM\Column(length=255, type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(length=255, type="string", nullable=true)
     */
    private $code;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

}