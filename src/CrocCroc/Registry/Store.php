<?php
/**
 * [complete this summary]
 * Created by gach.
 * Date: 19/01/16
 * Time: 12:59
 */

namespace CrocCroc\Registry;

use CrocCroc\Registry\Base\StoreInterface;

/**
 * Class Store
 * @package CrocCroc\Registry
 */
class Store implements StoreInterface {

    protected $data = [];

    /**
     * return value of $namespace registry key
     * return null if key is unknown
     *
     * @param string $namespace
     * @return null|mixed
     */
    public function get(string $namespace)
    {
        if(array_key_exists($namespace , $this->data)) {
            return $this->data[$namespace];
        }
        return null;
    }

    /**
     * set value for namespace
     *
     * @param string $namespace
     * @param $data
     * @return $this
     */
    public function set(string $namespace, $data)
    {
        $this->data[$namespace] = $data;
        return $this;
    }

    /**
     * return true if $namespace is known
     *
     * @param string $namespace
     * @return bool
     */
    public function has(string $namespace): bool
    {
        return array_key_exists($namespace , $this->data);
    }


}