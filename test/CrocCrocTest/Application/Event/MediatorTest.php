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


    public function testDelegateConstructor() {
        $this->assertSame($this->instance , $this->instance->delegateConstructor());
    }

    /**
     * test on method
     */
    public function testAddEvent() {

        $fixtureFunction = function($event) {
          return $event;
        };

        $priority = 1;
        $listener = $this->getMock('stdClass' , ['test']);

        $fixtureEventName = "eventTest";

        /**
         * first argument  => callback
         * second argument => unique call
         */
        $expectedEvents = [
            $fixtureEventName => [
                $priority =>
                [
                    $fixtureFunction, true
                ],
            ],
        ];
        /**
         * test event entry creation
         */


        $this->assertSame($this->instance , $this->callInaccessibleMethod( 'addEvent' , [$fixtureEventName , $fixtureFunction , true , $priority] ));

        $events = $this->getInaccessiblePropertyValue('events');

        $this->assertEquals($expectedEvents , $events);

        /**
         * test add callback to an existing event
         */
        $priority = 2;

        $this->assertSame($this->instance , $this->callInaccessibleMethod( 'addEvent' , [$fixtureEventName , [$listener , 'test' ], false , $priority]));

        $events = $this->getInaccessiblePropertyValue('events');

        $expectedEvents[$fixtureEventName][$priority] =
                [
                    [$listener , 'test' ], false ,
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

        $priority = 1;

        $this->instance = $this->getMock('CrocCroc\Application\Event\Mediator' , ['addEvent']);

        $this->instance->expects($this->once())
            ->method('addEvent')
            ->with($fixtureEventName , $fixtureFunction , false , $priority)
            ->willReturn($this->instance);

        $this->assertSame( $this->instance , $this->instance->on($fixtureEventName , $fixtureFunction , $priority));

    }

    /**
     * test once
     */
    public function testOnce() {

        $fixtureFunction = function($event) {
            return $event;
        };

        $priority = 1 ;

        $fixtureEventName = "eventTest";

        $this->instance = $this->getMock('CrocCroc\Application\Event\Mediator' , ['addEvent']);

        $this->instance->expects($this->once())
            ->method('addEvent')
            ->with($fixtureEventName , $fixtureFunction , true , $priority)
            ->willReturn($this->instance);

        $this->assertSame( $this->instance , $this->instance->once($fixtureEventName , $fixtureFunction , $priority));

    }



    public function testEmit() {

        $mockEvent = $this->getMock('CrocCroc\Application\Event\Event' , ['setData' , 'setEventName']);

        $fixtureData = ['data' => new \stdClass()];
        $fixtureEventName = 'eventTest';

        $priority  = 1;
        $priority2 = 2;
        $priority3 = 0;
        $mockEvent->expects($this->exactly(3))
                    ->method('setData')
                    ->with($fixtureData)
                    ->willReturn($mockEvent);

        $mockEvent->expects($this->exactly(3))
            ->method('setEventName')
            ->with($fixtureEventName)
            ->willReturn($mockEvent);

        $injector = $this->setMockInjector('CrocCroc\Application\Event\Event' , $mockEvent);

        $mockListener = $this->getMock('stdClass' , ['event','event2' ,'event3'] );

        $mockListener->expects($this->once())->method('event')->with($mockEvent)->willReturn(true);
        $mockListener->expects($this->once())->method('event2')->with($mockEvent)->willReturn(true);
        $mockListener->expects($this->once())->method('event3')->with($mockEvent)->willReturn(true);


        $event1 = [[$mockListener , 'event' ] , false];
        $event2 = [[$mockListener , 'event2' ] , true];
        $event3 = [[$mockListener , 'event3' ] , false];

        $fixtureEvent = [
            'eventTest' =>
                [
                    $priority2 => $event1, $priority  => $event2 , $priority3 => $event3
                ],
        ];
        $this->setInaccessiblePropertyValue('injector' , $injector);
        $this->setInaccessiblePropertyValue('events' , $fixtureEvent);

        $this->assertSame($this->instance , $this->instance->emit($fixtureEventName , $fixtureData));

        $events =  $this->getInaccessiblePropertyValue('events');

        $this->assertSame([$priority3 => $event3 , $priority2 => $event1] , $events['eventTest']);

    }

}