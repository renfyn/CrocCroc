<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 14:26
 */

namespace CrocCrocTest\Application\Injector;


use CrocCroc\Application\Injector\Factory\DefaultFactory;
use Interop\Container\ContainerInterface;

class DefaultFactoryTest extends \PhpunitTestCase
{

    /**
     * @var DefaultFactory
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new DefaultFactory();
    }


    public function invokeDataProvider() {



        $mockService   = $this->getMock('\CrocCroc\Application\Injector\Service\ServiceInterface' , ['getInjector' , 'setInjector' , 'delegateConstructor']);



        return
            [
                ['\stdClass' , null ,  false , false],
                //[get_class($mockService) , $mockService , false , true],
                ['\unknownClass' , null, true , false],
            ];

    }

    /**
     * @param string $className
     * @param \PHPUnit_Framework_MockObject_MockObject $mock
     * @param bool $exceptionExpected
     * @param bool $containerInjection
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(string $className,  $mock  , bool $exceptionExpected , bool $containerInjection) {

        $mockContainer = $this->getMock('CrocCroc\Application\Injector\Container' , ['get' , 'has', 'unStoredNextObject']);

        $factory = ($this->instance);

        if($containerInjection) {
            /**
             * @var \PHPUnit_Framework_MockObject_MockObject $mock
             */

            $mock->expects($this->once())
                ->method('setInjector')
                ->with($mockContainer)
                ->willReturn($mock);

            $mock->expects($this->once())
                ->method('delegateConstructor')
                ->willReturn(true);
        }

        if($exceptionExpected) {
            $this->setExpectedException('\CrocCroc\Application\Injector\Exception\NotFoundException');
        }

        $this->assertInstanceOf($className , $factory($className , $mockContainer));
    }

}
