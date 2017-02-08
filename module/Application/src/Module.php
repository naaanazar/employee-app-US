<?php

namespace Application;

use Application\View\Helper\Locale;
use Application\View\Helper\Route;
use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;
use Zend\Validator\AbstractValidator;

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
     * @var null|\Application\Back\Translator\Translator
     */
    private static $translator;

    /**
     * @var null|EntityManager
     */
    private static $entityManager;

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
        return include __DIR__ . '/../config/service.config.php';
    }

    /**
     * @return array
     */
    public function getViewHelperConfig() {
        return include __DIR__ . '/../config/service.config.php';
    }

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $this->setTranslator($event);
        $this->setEntityManager($event);
    }

    /**
     * @return null|Translator
     */
    public static function translator()
    {
        return static::$translator;
    }

    /**
     * @return EntityManager|null
     */
    public static function entityManager()
    {
        return static::$entityManager;
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
        AbstractValidator::setDefaultTranslator($translator);
        static::$translator = $translator;
        $serviceManager->get('ViewHelperManager')->get('translate')->setTranslator($translator);
    }

    /**
     * @param MvcEvent $event
     */
    public function setEntityManager(MvcEvent $event)
    {
        static::$entityManager = $event
            ->getApplication()
            ->getServiceManager()
            ->get('Doctrine\ORM\EntityManager');
    }

}
