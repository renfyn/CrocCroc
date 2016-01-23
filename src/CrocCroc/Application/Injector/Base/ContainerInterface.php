<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 15/01/16
 * Time: 21:54
 */

namespace CrocCroc\Application\Injector\Base;
use CrocCroc\Application\Injector\Factory\FactoryInterface;
use Interop\Container\ContainerInterface as PsrContainerInterface;

/**
 * Interface InjectorInterface
 * @package CrocCroc\Injector\Base
 */
interface ContainerInterface extends PsrContainerInterface , FactoryInterface {

    /**
     *
     *
     * @return ContainerInterface $this
     */
    public function unStoredNextObject();

}