<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 06:09
 */

namespace CrocCroc\Application\Manager;

interface ManagerInterface
{
    /**
     * @param string $className
     * @return $this
     */
    public function setConfigClassName(string $className);
    /**
     * @return $this
     */
    public function setConfig();

    /**
     * @return $this
     */
    public function init();

    /**
     * @return $this
     */
    public function run();

}