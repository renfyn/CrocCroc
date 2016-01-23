<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 14:11
 */


namespace CrocCroc\Application\Injector\Factory;

use CrocCroc\Application\Injector\Exception\NotFoundException;
use Interop\Container\ContainerInterface;

class DefaultFactory
{

    /**
     * create a new instance of $className if exists
     *
     * @param string $className
     * @param ContainerInterface $Container
     * @return object
     * @throws NotFoundException
     */
    public function __invoke(string $className , ContainerInterface $Container) {

        if(class_exists($className)) {

            $instance = new $className();

            if(is_subclass_of( $instance , '\CrocCroc\Application\Injector\Service\ServiceInterface')) {
                /**
                 * @var \CrocCroc\Application\Injector\Service\ServiceInterface $instance
                 */
                $instance->setInjector($Container)->delegateConstructor();
            }

            return $instance;

        }

        throw new NotFoundException('unable to load ' . $className);

    }


}