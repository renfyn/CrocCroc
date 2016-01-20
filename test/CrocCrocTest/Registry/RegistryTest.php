<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 20/01/16
 * Time: 22:39
 */

namespace CrocCrocTest\Registry;

use CrocCroc\Registry\Registry;

class RegistryTest extends \PhpunitTestCase
{
    /**
     * @var Registry
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Registry();
    }

    public function testSet() {

        $nameSpace    = 'test';
        $classFixture = new \stdClass();


        $nameSpaceScalar = 'scalar';
        $scalarValue     = 'test123';

        $store = $this->getMock('\CrocCroc\Registry\Store' , ['set']);

        $store->expects($this->once())
            ->method('set')
            ->with($nameSpace , $classFixture)
            ->willReturn($store);

        $className = get_class($store);

        $factory = $this->getMock('\stdClass' , ['factory'] );

        $factory->expects($this->once())
            ->method('factory')
            ->with($className)
            ->willReturn($store);


        $this->setInaccessiblePropertyValue('factory' , [$factory , 'factory']);
        $this->setInaccessiblePropertyValue('storeClassName' , $className);

        $expected = [
            $nameSpace       => $store,
            $nameSpaceScalar => $scalarValue,
        ];

        $this->assertSame($this->instance , $this->instance->set($nameSpace , $classFixture ));
        $this->assertSame($this->instance , $this->instance->set($nameSpaceScalar , $scalarValue ));
        $this->assertSame($expected , $this->getInaccessiblePropertyValue('data'));

    }
}