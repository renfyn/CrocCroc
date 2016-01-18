<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17/01/16
 * Time: 08:12
 */

namespace CrocCrocTest\Application\Injector;


use CrocCroc\Application\Injector\Container;

class ContainerTest extends \PhpunitTestCase
{
    /**
     * @var Container
     */
    protected $instance;

    /**
     * set up
     */
    public function setUp()
    {
        $this->instance = new Container();
    }

    /**
     *
     */
    public function testGetSetHasAliases() {

        $fixtureAlias = [
            'alias1' => 'class1',
            'alias2' => 'class2',
            'alias3' => 'class3',
        ];

        $this->assertSame($this->instance , $this->instance->setAliases($fixtureAlias));
        $this->assertSame($fixtureAlias , $this->instance->getAliases());
        $this->assertTrue( $this->instance->hasAlias('alias1'));
        $this->assertFalse( $this->instance->hasAlias('alias4'));

    }

    public function testAddAlias() {

        $fixtureAlias = 'alias1';
        $fixtureClass = 'Class1';

        $this->assertSame($this->instance , $this->instance->addAliases($fixtureAlias , $fixtureClass));

        $aliases = $this->getInaccessiblePropertyValue('aliases');

        $this->assertEquals($aliases , [$fixtureAlias => $fixtureClass]);

    }

    public function GetDataProvider() {

        $mockClass    = $this->getMock('StdClass');

        $className    = get_class($mockClass);

        $mockClass2   = $this->getMock('StdClass');
        $className2   = get_class($mockClass2);
        $aliases      = ['alias' => get_class($mockClass2)];

        return [

            [$className , $className , $mockClass , [] , [] , false],
            ['alias',  $className2  , $mockClass2 , $aliases , [] , false],
            ['alias',  $className2  , $mockClass2 , $aliases , [$className2 => $mockClass2], false ],
            ['alias2',  'StdClassFalse'  , null , $aliases , [], true ],
            ['StdClassFalse',  'StdClassFalse'  , null , $aliases , [], true ],

        ];

    }

    public function setFactoryMock($className , $mockClass) {

        $mockFactory = $this->getMock('StdClass' , ['__invoke']);

        $mockFactory->expects($this->once())
            ->method('__invoke')
            ->with($className , $this->instance)
            ->willReturn($mockClass);

        $this->setInaccessiblePropertyValue('factory' , $mockFactory);
    }

    /**
     * @dataProvider GetDataProvider
     * @param $id
     * @param $className
     * @param $mockClass
     * @param $aliases
     * @param $instances
     * @param $exception
     */
    public function testGet($id , $className , $mockClass , $aliases , $instances , $exception ) {

        if($exception) {

            $this->setExpectedException('CrocCroc\Application\Injector\Exception\NotFoundException');
        } else {
            if(empty($instances)) {

                $this->setFactoryMock($className , $mockClass);

            }
        }

        $this->setInaccessiblePropertyValue('aliases' , $aliases);
        $this->setInaccessiblePropertyValue('instances' , $instances);

        $this->assertSame($mockClass , $this->instance->get($id));

        $storedInstance = $this->getInaccessiblePropertyValue('instances');

        $this->assertSame($storedInstance[$className] , $mockClass);

    }

    public function testGetNoneStored() {

        $mockClass   = $this->getMock('StdClass');

        $className = get_class($mockClass);

        $this->setInaccessiblePropertyValue('storedNextObject' , false);

        $this->setFactoryMock($className , $mockClass);

        $this->assertInstanceOf($className , $this->instance->get($className));

        $storedInstance = $this->getInaccessiblePropertyValue('instances');

        $this->assertFalse(array_key_exists($className , $storedInstance));
    }

    public function testSetGetFactory() {

        $fixtureFactory = function() {};

        $this->assertSame($this->instance , $this->instance->setFactory($fixtureFactory));
        $this->assertSame($fixtureFactory , $this->instance->getFactory());

    }

    public function HasDataProvider() {

        $aliases      = ['alias1' => 'StdClass'];

        return [

            ['StdClass' , $aliases , true],
            ['alias1' , $aliases , true],
            ['StdClassFalse' , $aliases , false],
            ['alias2' , $aliases , false],

        ];

    }

    /**
     * @dataProvider HasDataProvider
     */
    public function testHas($id  , $aliases , $expected ) {

        $this->setInaccessiblePropertyValue('aliases' , $aliases);

        $this->assertSame( $expected ,$this->instance->has($id));

    }

    public function testUnStoredNextObject() {

        $this->assertSame($this->instance , $this->instance->unStoredNextObject());

        $storedNextObject = $this->getInaccessiblePropertyValue('storedNextObject');

        $this->assertFalse($storedNextObject);

    }

    /**
     * tear down
     */
    public function tearDown()
    {
        $this->instance = null;
    }

}
