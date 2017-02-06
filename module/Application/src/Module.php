<?php

namespace Application;

use Application\View\Helper\Locale;
use Zend\I18n\Translator\Translator;
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

    private static $translator = null;

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
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $this->setTranslator($event);
    }

    /**
     * @return null|Translator
     */
    public static function translator()
    {
        return static::$translator;
    }

    /**
     * @param MvcEvent $event
     */
    public function setTranslator(MvcEvent $event)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $event->getApplication()->getServiceManager();
        $storage = new Container('language');
        $locale = $storage->offsetExists('language') ? $storage->offsetGet('language') : 'en_US';

        $translator = $serviceManager->get('translator')->setLocale($locale);
        static::$translator = $translator;
        $serviceManager->get('ViewHelperManager')->get('translate')->setTranslator($translator);
    }

}
