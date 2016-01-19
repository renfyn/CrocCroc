<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 18/01/16
 * Time: 21:39
 */

namespace CrocCroc\Application\Event;

use CrocCroc\Application\Event\Base\MediatorInterface;

class Mediator implements MediatorInterface {

    /**
     * @var array
     */
    protected $events = [];

    /**
     * event class pass to callback
     * @var string
     */
    protected $eventClassName;

    /**
     * add a callback to event store
     *
     * @param string $eventName
     * @param callable $callBack
     * @param bool $unique
     * @return $this
     */
    protected function addEvent(string $eventName, callable $callBack , bool $unique = false) {
        $newEvent = [
            $callBack , $unique
        ];

        if(array_key_exists($eventName , $this->events)) {
            $this->events[$eventName][] = $newEvent;
        } else {
            $this->events[$eventName] = [$newEvent];
        }
        return $this;
    }

    /**
     * attach any callable to an event
     *
     * @param string $eventName
     * @param callable $callBack
     * @return Mediator $this
     */
    public function on(string $eventName, callable $callBack)
    {
        return $this->addEvent($eventName , $callBack , false);
    }

    /**
     * attach any callable to an event just one time
     *
     * @param string $eventName
     * @param callable $callBack
     * @return Mediator $this
     */
    public function once(string $eventName, callable $callBack)
    {
        return $this->addEvent($eventName , $callBack , true);
    }

    /**
     * trigger an event
     *
     * @param string $eventName
     * @param array $data
     * @return $this
     */
    public function emit(string $eventName, array $data = array())
    {
        // TODO: Implement emit() method.
    }



}