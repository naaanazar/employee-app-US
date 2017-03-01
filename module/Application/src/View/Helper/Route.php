<?php

namespace Application\View\Helper;

use Application\Back\Locale\Manager;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Locale
 * @package Application\View
 */
class Route extends AbstractHelper
{

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * Locale constructor.
     * @param $routeMatch
     */
    public function __construct(RouteMatch $routeMatch) {
        $this->routeMatch = $routeMatch;
    }

    /**
     * @param array $replaceArray
     * @return array
     */
    public function getParams(array $replaceArray = [])
    {
        return array_replace($this->routeMatch->getParams(), $replaceArray);
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return $this->routeMatch->getParam($name, $default);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->routeMatch->getMatchedRouteName();
    }
}