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



    public function testEmit() {

        $mockEvent = $this->getMock('CrocCroc\Application\Event\Event' , ['setData' , 'setEventName']);

        $fixtureData = ['data' => new \stdClass()];
        $fixtureEventName = 'eventTest';

        $mockEvent->expects($this->exactly(2))
                    ->method('setData')
                    ->with($fixtureData)
                    ->willReturn($mockEvent);

        $mockEvent->expects($this->exactly(2))
            ->method('setEventName')
            ->with($fixtureEventName)
            ->willReturn($mockEvent);

        $injector = $this->setMockInjector('CrocCroc\Application\Event\Event' , $mockEvent);

        $mockListener = $this->getMock('stdClass' , ['event','event2'] );

        $mockListener->expects($this->once())->method('event')->with($mockEvent)->willReturn(true);
        $mockListener->expects($this->once())->method('event2')->with($mockEvent)->willReturn(true);

        $event1 = [[$mockListener , 'event' ] , false];
        $event2 = [[$mockListener , 'event2' ] , true];

        $fixtureEvent = [
            'eventTest' =>
                [
                    $event1,$event2
                ],
        ];
        $this->setInaccessiblePropertyValue('injector' , $injector);
        $this->setInaccessiblePropertyValue('events' , $fixtureEvent);

        $this->assertSame($this->instance , $this->instance->emit($fixtureEventName , $fixtureData));

        $events =  $this->getInaccessiblePropertyValue('events');

        $this->assertSame([$event1] , $events['eventTest']);

    }

}