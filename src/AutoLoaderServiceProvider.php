<?php

namespace Nip\AutoLoader;

use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;

/**
 * Class AutoLoaderServiceProvider
 * @package Nip\AutoLoader
 */
class AutoLoaderServiceProvider extends AbstractSignatureServiceProvider
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
        $autoloader = self::newAutoLoader();
        $this->getContainer()->share('autoloader', $autoloader);
    }

    /**
     * @return AutoLoader
     */
    public static function newAutoLoader()
    {
        return new AutoLoader();
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['autoloader'];
    }
}
