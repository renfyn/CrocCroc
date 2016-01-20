<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 15/01/16
 * Time: 21:16
 */

namespace CrocCroc\Application\Base;
use CrocCroc\Config\Base\ConfigInterface;
use CrocCroc\Application\Injector\Base\ContainerInterface;

/**
 * Interface ApplicationInterface
 * @package CrocCroc\Application\Base
 */
interface ApplicationInterface {

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * @return ApplicationInterface $this
     */
    public function loadConfig(): ApplicationInterface;
    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface;

    /**
     * @param RouterInterface $router
     * @return ApplicationInterface $this
     */
    public function setRouter(RouterInterface $router): ApplicationInterface ;

    /**
     * @return ManagerInterface
     */
    public function getManager(): ManagerInterface;

    /**
     * @param ManagerInterface $manager
     * @return ApplicationInterface $this
     */
    public function setManager(ManagerInterface $manager): ApplicationInterface ;

    /**
     * @param ContainerInterface $injector
     * @return ApplicationInterface $this
     */
    public function setDefaultInjector(ContainerInterface $injector): ApplicationInterface;

    /**
     * @return ContainerInterface
     */
    public function getDefaultInjector(): ContainerInterface;

    /**
     * @return void
     */
    public function run();

}