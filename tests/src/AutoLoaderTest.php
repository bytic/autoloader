<?php

namespace ByTIC\Autoloader\Tests;

use Mockery as m;
use ByTIC\Autoloader\Autoloader;

/**
 * Class AutoLoaderTest
 * @package ByTIC\Autoloader\Autoloader
 */
class AutoLoaderTest extends AbstractTest
{
    /**
     * @var AutoLoader
     */
    protected $object;

    // tests

    public function testRegisterHandler()
    {
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->object = new Autoloader();
    }
}
