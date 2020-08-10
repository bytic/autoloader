<?php

namespace ByTIC\Autoloader;

use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;

/**
 * Class AutoloaderServiceProvider
 * @package ByTIC\Autoloader
 */
class AutoloaderServiceProvider extends AbstractSignatureServiceProvider
{

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerAutoLoader();
    }

    protected function registerAutoLoader()
    {
        $this->getContainer()->share('autoloader', function ()
        {
            return new Autoloader();
        });
    }

    /**
     * @return Autoloader
     */
    public static function newAutoLoader()
    {
        return new Autoloader();
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['autoloader'];
    }
}
