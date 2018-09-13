<?php

namespace Nip\AutoLoader\Loaders;

use Exception;
use Nip\AutoLoader\Generators\ClassMap as Generator;
use Nip\Utility\Text;

/**
 * Class Psr4Class.
 */
class ClassMap extends AbstractLoader
{
    protected $directories = [];

    protected $directoriesMap = [];

    /**
     * @var []|null
     */
    protected $map = null;

    /**
     * @var bool
     */
    protected $retry = false;

    /**
     * @var int
     */
    protected $retries = 0;

    /**
     * @param $dir
     *
     * @return $this
     */
    public function addDirectory($dir)
    {
        $this->directories[] = $dir;

        return $this;
    }

    /**
     * @param $class
     *
     * @return null|string
     */
    public function getClassLocation($class)
    {
        return $this->getClassMapLocation($class);
    }

    /**
     * @param $class
     * @param bool $retry
     *
     * @return null|string
     */
    protected function getClassMapLocation($class, $retry = true)
    {
        $this->checkMapInit();

        if (in_array($class, array_keys($this->getMap()))) {
            return $this->map[$class];
        }

        if ($this->isRetry() === false) {
            return false;
        }

        if ($retry === true && !$this->isMaxRetries()) {
            $this->generateMap();
            $this->increaseRetries();

            return $this->getClassMapLocation($class, false);
        }
    }

    protected function checkMapInit()
    {
        if ($this->map === null) {
            $this->initMap();
        }
    }

    protected function initMap()
    {
        $this->map = [];
        foreach ($this->directories as $dir) {
            $this->readMapDir($dir);
        }
    }

    /**
     * @param $dir
     */
    protected function readMapDir($dir)
    {
        $filePath = $this->getCachePath($dir);

        if (!$this->readCacheFile($filePath)) {
            $this->generateMapDir($dir);
            $this->readCacheFile($filePath);
        }
    }

    /**
     * @param $dir
     *
     * @return string
     */
    protected function getCachePath($dir)
    {
        $fileName = $this->getCacheName($dir);

        return $this->getAutoLoader()->getCachePath().$fileName;
    }

    /**
     * @param $dir
     *
     * @return string
     */
    public function getCacheName($dir)
    {
        return Text::toAscii($dir).'.php';
    }

    /**
     * @param $filePath
     *
     * @return bool
     */
    protected function readCacheFile($filePath)
    {
        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $map = require $filePath;
            if (is_array($map)) {
                $this->map = array_merge($this->map, $map);
            }

            return true;
        }

        return false;
    }

    /**
     * @param $dir
     *
     * @throws Exception
     */
    public function generateMapDir($dir)
    {
        $filePath = $this->getCachePath($dir);
        Generator::dump($dir, $filePath);
    }

    /**
     * @return array
     */
    protected function getMap()
    {
        $this->checkMapInit();

        return $this->map;
    }

    /**
     * @return bool
     */
    public function isRetry()
    {
        return $this->retry;
    }

    /**
     * @param bool $retry
     */
    public function setRetry($retry)
    {
        $this->retry = $retry;
    }

    public function generateMap()
    {
        foreach ($this->directories as $dir) {
            $this->generateMapDir($dir);
        }
    }

    /**
     * @param $dir
     *
     * @return bool
     */
    protected function hasMapFile($dir)
    {
        $filePath = $this->getCachePath($dir);

        return file_exists($filePath);
    }

    /**
     * @return int
     */
    public function isMaxRetries()
    {
        return $this->retries > 1;
    }

    /**
     * @param int $retries
     */
    public function increaseRetries()
    {
        $this->retries++;
    }
}
