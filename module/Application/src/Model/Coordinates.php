<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Coordinates
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="coordinates")
 */
class Coordinates
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(name="lat", precision=20, scale=18, type="decimal",  nullable=true)
     */
    private $lattitude;

    /**
     * @var float
     * @ORM\Column(name="lng", precision=20, scale=18, type="decimal",  nullable=true)
     */
    private $longitude;

    /**
     * @var EmploteeId
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="emplotee_id", referencedColumnName="id")
     */
    private $emploteeId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return EmploteeId
     */
    public function getEmploteeId()
    {
        return $this->emploteeId;
    }

    /**
     * @param float $lattitude
     * @return $this
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @param $emploteeId
     * @return $this
     */
    public function setEmploteeId($emploteeId)
    {
        $this->emploteeId = $emploteeId;

        return $this;
    }

}