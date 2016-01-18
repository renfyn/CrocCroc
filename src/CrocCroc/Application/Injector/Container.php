<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17/01/16
 * Time: 07:50
 */

namespace CrocCroc\Application\Injector;

use CrocCroc\Application\Injector\Base\ContainerInterface;
use CrocCroc\Application\Injector\Exception\ContainerException;
use CrocCroc\Application\Injector\Exception\NotFoundException;

/**
 * Class Container
 * @package CrocCroc\Injector
 */
final class Container implements ContainerInterface {

    /**
     *
     * @var bool
     */
    protected $storedNextObject = true;

    /**
     * List of the stored instances
     * @var array
     */
    protected $instances = [];

    /**
     * array of all class aliases
     * @var array
     */
    protected $aliases = [];

    /**
     * any callable
     * @var Callable
     */
    protected $factory;

    /**
     * return factory callable
     * @return Callable
     */
    public function getFactory():Callable
    {
        return $this->factory;
    }

    /**
     * @param Callable $factory
     * @return $this
     */
    public function setFactory(Callable $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * Return aliases list
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * set aliases list as :
     *
     * ['alias' => '\\namespace\\className']
     *
     * @param $aliases
     * @return $this
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * add a single class alias
     *
     * @param string $alias
     * @param string $className
     * @return $this
     */
    public function addAliases(string $alias ,string $className) {

        $this->aliases[$alias] = $className;
        return $this;
    }

    /**
     * return if alias exists
     * @param string $alias
     * @return bool
     */
    public function hasAlias(string $alias): bool {
        return array_key_exists($alias , $this->aliases);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * You can use an aliases added with addAlias or className Directly
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        $className = $id;

        if(array_key_exists($id , $this->aliases)) {
            $className = $this->aliases[$id];
        }

        if($this->storedNextObject && array_key_exists($className , $this->instances)) {
            return $this->instances[$className];
        }

        if(!class_exists($className, true)) {
            throw new NotFoundException('class ' . $className . ' not found');
            return false;
        }

        $factory = ($this->factory);

        $Object = $factory($className , $this);

        if($this->storedNextObject) {
            $this->instances[$className] = $Object;
        }

        return $Object;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * return true if alias exists
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundException`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        $className = $id;

        if(array_key_exists($id , $this->aliases)) {
            $className = $this->aliases[$id];
        }

        return class_exists($className , true);
    }

    /**
     * Use this method to get a new instance at the next get($id) call.
     * This new instance will not be stored.
     *
     * @return ContainerInterface this
     */
    public function unStoredNextObject(): ContainerInterface
    {
        $this->storedNextObject = false;
        return $this;
    }


}