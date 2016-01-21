<?php
/**
 * Created by gach.
 * Date: 19/01/16
 * Time: 12:58
 */

namespace CrocCroc\Registry;

use CrocCroc\Registry\Base\RegistryInterface;

class Registry implements RegistryInterface
{

    protected $data = [];

    protected $factory;

    protected $storeClassName;

    public function get(string $namespace)
    {
        // TODO: Implement get() method.
    }

    public function set(string $namespace, $data)
    {
        if(is_scalar($data)) {
            $this->data[$namespace] = $data;
        } else {
            $factory         = $this->factory;
            $storeClassName  = $this->storeClassName;
            $store           = $factory($storeClassName)->set($namespace, $data);

            $this->data[$namespace] = $store;
        }

        return $this;
    }

    public function has(string $namespace): bool
    {
        // TODO: Implement has() method.
    }


}