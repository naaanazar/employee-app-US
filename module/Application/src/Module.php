<?php

namespace Application;

use Application\View\Helper\Locale;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;

/**
 * Class Module
 * @package Application
 */
class Module
{

    /**
     * Version of alpha
     */
    const VERSION = '0.0.1-alpha';

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'locale' => function(ServiceManager $serviceManager) {
                    return new Locale($serviceManager);
                },
            )
        );
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $storage = new Container('language');
        $locale = $storage->offsetExists('language') ? $storage->offsetGet('language') : 'en_US';

        /** @var ServiceManager $serviceManager */
        $serviceManager = $e->getApplication()->getServiceManager();

        $translator = $serviceManager->get('translator')->setLocale($locale);
        $serviceManager->get('ViewHelperManager')->get('translate')->setTranslator($translator);
    }

}
