<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 19/01/16
 * Time: 06:38
 */

namespace CrocCrocTest\Application\Event;

use CrocCroc\Application\Event\Event;

class EventTest extends \PhpunitTestCase
{
    /**
     * @var Event
     */
    protected $instance;

    /**
     * set yp new event
     */
    public function setUp() {
        $this->instance = new Event;
    }

    public function testSetGetData() {
        $fixtureData = [
            'data', 'data1'
        ];

        $this->assertSame($this->instance , $this->instance->setData($fixtureData));
        $this->assertSame($fixtureData , $this->instance->getData());

    }

    public function testSetGetEventName() {
        $fixtureEventName = 'EventName';

        $this->assertSame($this->instance , $this->instance->setEventName($fixtureEventName));
        $this->assertSame($fixtureEventName , $this->instance->getEventName());

    }

}