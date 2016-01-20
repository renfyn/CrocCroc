<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 18/01/16
 * Time: 21:28
 */

namespace CrocCroc\Application\Event\Base;

/**
 * Mediator interface
 *
 * Interface MediatorInterface
 * @package CrocCroc\Application\Event\Base
 */
interface MediatorInterface {

    /**
     * attach any callable to an event
     *
     * @param string $eventName
     * @param callable $callBack
     * @param int $priority
     * @return $this
     */
    public function on(string $eventName  , callable $callBack , int $priority = 0);

    /**
     * attach any callable to an event just one time
     *
     * @param string $eventName
     * @param callable $callBack
     * @param int $priority
     * @return $this
     */
    public function once(string $eventName  , callable $callBack , int $priority = 0);

    /**
     * send an event
     *
     * @param string $eventName
     * @param array $data
     * @return $this
     */
    public function emit(string $eventName , array $data = array() );

}