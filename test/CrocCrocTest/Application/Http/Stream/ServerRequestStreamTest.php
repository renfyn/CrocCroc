<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 31/01/16
 * Time: 20:45
 */

namespace CrocCrocTest\Application\Http\Stream;

class ServerRequestStreamTest extends \PhpunitTestCase
{

    /**
     * @var \CrocCroc\Application\Http\Stream\ServerRequestStream
     */
    protected $instance;

    public function setUp() {
        $this->instance = new \CrocCroc\Application\Http\Stream\ServerRequestStream();
    }

    public function testConstructor() {
        $this->assertTrue(is_resource($this->getInaccessiblePropertyValue('resource')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isReadable')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isWritable')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isSeekable')));
    }

}