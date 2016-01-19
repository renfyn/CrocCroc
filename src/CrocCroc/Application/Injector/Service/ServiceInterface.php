<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 16/01/16
 * Time: 08:24
 */

namespace CrocCroc\Application\Injector\Service;

use CrocCroc\Application\Injector\Base\ContainerInterface;

/**
 * Interface ServiceInterface
 * @package CrocCroc\Injector\Service
 */
interface ServiceInterface {
    /**
     * @return ContainerInterface
     */
    public function getInjector();

    /**
     * @param ContainerInterface $injector
     * @return ServiceInterface $this
     */
    public function setInjector(ContainerInterface $injector);

    /**
     *
     *
     * @return mixed
     */
    public function delegateConstructor() ;

}