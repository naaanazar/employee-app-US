<?php

namespace Application\Back\Form\Search;

use Zend\Paginator\Paginator;

/**
 * Class AbstractSearch
 * @package Application\Back\Form\Search
 */
abstract class AbstractSearch
{

    /**
     * @var array
     */
    protected $data;

    /**
     * AbstractSearch constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function data($key, $default = null)
    {
        if (true === isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * @return Paginator|array
     */
    abstract public function getResult();
}