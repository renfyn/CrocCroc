<?php

/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 07:53
 */

namespace CrocCroc\Application\Injector\Factory;

/**
 * trait FactoryInterface
 */
trait FactoryTrait {

    /**
     * @var callable
     */
    protected $factory;

    /**
     * inject any callable factory
     *
     * @param Callable $factory
     * @return $this
     */
    public function setFactory(callable $factory) {
        $this->factory = $factory;
        return $this;
    }

    /**
     * return factory callable
     * @return Callable
     */
    public function getFactory() : callable {
        return $this->factory;
    }


}