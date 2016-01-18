<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 15/01/16
 * Time: 21:54
 */

namespace CrocCroc\Application\Injector\Base;
use Interop\Container\ContainerInterface as PsrContainerInterface;

/**
 * Interface InjectorInterface
 * @package CrocCroc\Injector\Base
 */
interface ContainerInterface extends PsrContainerInterface {

    /**
     *
     *
     * @return ContainerInterface $this
     */
    public function unStoredNextObject(): ContainerInterface ;

}