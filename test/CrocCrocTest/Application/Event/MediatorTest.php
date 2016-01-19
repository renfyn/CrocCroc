<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 18/01/16
 * Time: 21:00
 */

namespace CrocCrocTest\Application\Event;

use CrocCroc\Application\Event\Mediator;

class MediatorTest extends \PhpunitTestCase
{

    /**
     * @var Mediator
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Mediator();
    }

    /**
     * test on method
     */
    public function testAddEvent() {

        $fixtureFunction = function($event) {
          return $event;
        };

        $listener = $this->getMock('stdClass' , ['test']);

        $fixtureEventName = "eventTest";

        /**
         * first argument  => callback
         * second argument => unique call
         */
        $expectedEvents = [
            $fixtureEventName => [
                [
                    $fixtureFunction, false
                ],
            ],
        ];
        /**
         * test event entry creation
         */
        $this->assertSame($this->instance , $this->callInaccessibleMethod( 'addEvent' , [$fixtureEventName , $fixtureFunction]));

        $events = $this->getInaccessiblePropertyValue('events');

        $this->assertEquals($expectedEvents , $events);

        /**
         * test add callback to an existing event
         */
        $this->assertSame($this->instance , $this->callInaccessibleMethod( 'addEvent' , [$fixtureEventName , [$listener , 'test' ]]));

        $events = $this->getInaccessiblePropertyValue('events');

        $expectedEvents[$fixtureEventName][] =
                [
                    [$listener , 'test' ], false
                ];

        $this->assertEquals($expectedEvents , $events);

    }

    /**
     * test on
     */
    public function testOn() {

        $fixtureFunction = function($event) {
            return $event;
        };

        $fixtureEventName = "eventTest";

        $this->instance = $this->getMock('CrocCroc\Application\Event\Mediator' , ['addEvent']);

        $this->instance->expects($this->once())
            ->method('addEvent')
            ->with($fixtureEventName , $fixtureFunction , false)
            ->willReturn($this->instance);

        $this->assertSame( $this->instance , $this->instance->on($fixtureEventName , $fixtureFunction));

    }

    /**
     * test once
     */
    public function testOnce() {

        $fixtureFunction = function($event) {
            return $event;
        };

        $fixtureEventName = "eventTest";

        $this->instance = $this->getMock('CrocCroc\Application\Event\Mediator' , ['addEvent']);

        $this->instance->expects($this->once())
            ->method('addEvent')
            ->with($fixtureEventName , $fixtureFunction , true)
            ->willReturn($this->instance);

        $this->assertSame( $this->instance , $this->instance->once($fixtureEventName , $fixtureFunction));

    }

}