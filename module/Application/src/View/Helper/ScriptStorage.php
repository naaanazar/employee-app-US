<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class ScriptStorage
 * @package Application\View\Helper
 */
class ScriptStorage extends AbstractHelper implements \Iterator
{

    /**
     * @var array
     */
    protected $scripts = [];

    /**
     * ScriptStorage constructor.
     * @param $scripts
     */
    public function __construct($scripts)
    {
        $this->scripts = $scripts;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->scripts);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->scripts);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->scripts);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return current($this->scripts) !== false;
    }

    /**
     * @return bool
     */
    public function rewind()
    {
        return reset($this->scripts);
    }

}