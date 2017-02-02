<?php

namespace Application;

/**
 * Class Module
 * @package Application
 */
class Module
{
    const VERSION = '3.0.2dev';

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
}
