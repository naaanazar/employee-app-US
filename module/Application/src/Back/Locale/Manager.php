<?php

namespace Application\Back\Locale;

use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;

class Manager
{

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * Manager constructor.
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return array
     */
    public function getList()
    {
        $config = $this->serviceManager->get('Config');
        return isset($config['locales']) ? $config['locales'] : [];
    }

    /**
     * @return mixed|string
     */
    public function getCurrentLocale()
    {
        $storage = (new Container('language'));
        return $storage->offsetExists('language') ? $storage->offsetGet('language') : 'en_US';
    }

}