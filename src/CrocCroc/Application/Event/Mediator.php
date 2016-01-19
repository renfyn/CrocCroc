<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 18/01/16
 * Time: 21:39
 */

namespace CrocCroc\Application\Event;

use CrocCroc\Application\Event\Base\MediatorInterface;
use CrocCroc\Application\Injector\Service\ServiceInterface;
use CrocCroc\Application\Injector\Service\ServiceTrait;

class Mediator implements MediatorInterface, ServiceInterface {

    use ServiceTrait;
    /**
     * @var array
     */
    protected $events = [];

    /**
     * event class pass to callback
     * @var string
     */
    protected $eventClassName;

    public function delegateConstructor()
    {
    }

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
        if(array_key_exists($eventName , $this->events)) {
            /**
             * @var \CrocCroc\Application\Event\Event $eventValue
             */
            $eventValue = $this->getInjector()->get('CrocCroc\Application\Event\Event');

            foreach($this->events[$eventName] as $index => $listener) {

                $eventValue->setData($data)->setEventName($eventName);
                $unique   = ($listener[1]);
                $callBack = ($listener[0]);
                $callBack($eventValue);
                if($unique) {
                    unset($this->events[$eventName][$index]);
                }
            }

        }

        return $this;
    }



}