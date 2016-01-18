<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17/01/16
 * Time: 22:19
 */

namespace CrocCroc\Application\Injector\Service;

use CrocCroc\Application\Injector\Base\ContainerInterface;


trait ServiceTrait {

    protected $injector;

    /**
     * @return ContainerInterface
     */
    public function getInjector(): ContainerInterface {
        return $this->injector;
    }

    /**
     * @param ContainerInterface $injector
     * @return ServiceInterface $this
     */
    public function setInjector(ContainerInterface $injector): ServiceInterface {
        $this->injector = $injector;
        return $this;
    }

}