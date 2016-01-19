<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 19/01/16
 * Time: 06:47
 */

namespace CrocCroc\Application\Event;

use CrocCroc\Application\Event\Base\EventInterface;

/**
 * Class Event
 * @package CrocCroc\Application\Event
 */
class Event implements EventInterface
{

    protected $data;

    protected $eventName;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
        return $this;
    }



}