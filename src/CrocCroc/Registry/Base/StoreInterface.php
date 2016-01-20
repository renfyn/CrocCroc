<?php
/**
 * Created by gach.
 * Date: 19/01/16
 * Time: 13:00
 */

namespace CrocCroc\Registry\Base;

/**
 * Interface StoreInterface
 * @package CrocCroc\Registry\Base
 */
interface StoreInterface {

    /**
     * @param string $namespace
     * @return mixed
     */
    public function get(string $namespace);

    /**
     * @param string $namespace
     * @param $data
     * @return $this
     */
    public function set(string $namespace , $data);

    /**
     * @param string $namespace
     * @return bool
     */
    public function has(string $namespace): bool ;

}