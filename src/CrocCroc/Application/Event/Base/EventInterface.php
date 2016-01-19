<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 19/01/16
 * Time: 06:35
 */

namespace CrocCroc\Application\Event\Base;

/**
 * Interface EventInterface
 * @package CrocCroc\Application\Event\Base
 */
interface EventInterface {

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param string $event
     * @return $this
     */
    public function setEventName(string $event);

    /**
     * @return string
     */
    public function getEventName(): string;

}