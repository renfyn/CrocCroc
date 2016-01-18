<?php
/**
 * [complete this summary]
 * Created by gach.
 * Date: 18/01/16
 * Time: 10:31
 */

namespace CrocCrocTest\Application\Injector\Service;

class ServiceTraitTest extends \PhpunitTestCase {

    public function setUp()
    {
        $this->instance = $this->getMockForTrait('CrocCroc\Application\Injector\Service\ServiceTrait');
    }

    public function testSetGetInjector() {

        $MockInjector = new \CrocCroc\Application\Injector\Container;

        $this->assertSame($this->instance , $this->instance->setInjector($MockInjector));
        $this->assertSame($MockInjector , $this->instance->getInjector());

    }

}