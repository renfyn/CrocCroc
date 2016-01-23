<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 23/01/16
 * Time: 06:09
 */

namespace CrocCroc\Application\Manager;

use CrocCroc\Application\Config\Base\ConfigInterface;

interface ManagerInterface
{
    /**
     * @param string $className
     * @return $this
     */
    public function setConfigClassName(string $className);

    /**
     * @param ConfigInterface $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config);

    /**
     * @return $this
     */
    public function init();

    /**
     * @return $this
     */
    public function run();

}