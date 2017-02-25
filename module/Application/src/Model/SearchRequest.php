<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SearchRequest
 * @package Application\Model
 * @ORM\Entity
 * @ORM\Table(name="search_requests")
 */
class SearchRequest
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
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $params;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $lastSearch;

    /**
     * @var bool
     * @ORM\Column(name="is_found", type="boolean")
     */
    private $found;

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
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return \DateTime
     */
    public function getLastSearch(): \DateTime
    {
        return $this->lastSearch;
    }

    /**
     * @return bool
     */
    public function isFound(): bool
    {
        return $this->found;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param \DateTime $lastSearch
     */
    public function setLastSearch(\DateTime $lastSearch)
    {
        $this->lastSearch = $lastSearch;
    }

    /**
     * @param bool $found
     */
    public function setFound(bool $found)
    {
        $this->found = $found;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

}