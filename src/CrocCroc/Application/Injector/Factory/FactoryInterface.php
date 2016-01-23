<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 07:53
 */

namespace CrocCroc\Application\Injector\Factory;

/**
 * Interface FactoryTrait
 */
interface FactoryInterface {

    /**
     * inject any callable factory
     *
     * @param Callable $factory
     * @return $this
     */
    public function setFactory(callable $factory);

    /**
     * return factory callable
     * @return Callable
     */
    public function getFactory() : callable ;


}