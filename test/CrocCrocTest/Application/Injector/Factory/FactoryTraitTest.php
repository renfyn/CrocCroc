<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 13:39
 */

namespace CrocCrocTest\Application\Injector;


class FactoryTraitTest extends \PhpunitTestCase
{

    public function setUp() {

        $this->instance = $this->getMockForTrait('CrocCroc\Application\Injector\Factory\FactoryTrait');

    }

    public function testSetGetFactory() {

        $fixtureFactory = function() {};

        $this->assertSame($this->instance , $this->instance->setFactory($fixtureFactory));
        $this->assertSame($fixtureFactory , $this->instance->getFactory());

    }

}