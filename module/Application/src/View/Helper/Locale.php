<?php

namespace Application\View\Helper;

use Application\Back\Locale\Manager;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Locale
 * @package Application\View
 */
class Locale extends AbstractHelper
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Locale constructor.
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return Manager
     */
    public function __invoke() {
        return new Manager($this->serviceManager);
    }

}