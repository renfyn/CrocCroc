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

        return
            [
                ['\stdClass'  ,  false , false],
                ['\CrocCroc\Application\Event\Mediator'  , false , true],
                ['\unknownClass' , true , false],
            ];

    }

    /**
     * @param string $className
     * @param bool $exceptionExpected
     * @param bool $containerInjection
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(string $className  , bool $exceptionExpected , bool $containerInjection) {

        $mockContainer = $this->getMock('CrocCroc\Application\Injector\Container' , ['get' , 'has', 'unStoredNextObject']);

        $factory = ($this->instance);



        if($exceptionExpected) {
            $this->setExpectedException('\CrocCroc\Application\Injector\Exception\NotFoundException');
        }

        $class = $factory($className , $mockContainer);

        $this->assertInstanceOf($className , $class);

        if($containerInjection) {
            $this->assertSame($mockContainer , $class->getInjector());
        }
    }

}
