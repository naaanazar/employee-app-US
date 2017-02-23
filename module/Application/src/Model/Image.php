<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Image
 * @package Application\Model
 * @ORM\Entity(repositoryClass="Application\Model\Repository\ImageRepository")
 * @ORM\Table(name="images")
 */
class Image
{

    const DEFAULT_IMAGE = 'img/user-profile.png';
    const DEFAULT_THUMB = 'img/user-profile-thumb.png';

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
    private $original;

    /**
     * @var string
     * @ORM\Column(type="string",  nullable=true)
     */
    private $thumbnail;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }

    /**
     * @return string
     */
    public function getThumbnail(): string
    {
        if (null === $this->thumbnail) {
            return $this->original;
        }

        return $this->thumbnail;
    }

    /**
     * @param string $original
     */
    public function setOriginal(string $original)
    {
        $this->original = $original;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail(string $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

}