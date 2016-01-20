<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 20/01/16
 * Time: 22:01
 */

namespace CrocCrocTest\Registry;

use CrocCroc\Registry\Store;

class StoreTest extends \PhpunitTestCase {

    /**
     * @var Store
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Store();
    }

    public function testSetGetHas() {

        $fixtureNameSpace = 'namespaceTest';

        $unexistsNamespace = 'unexistsNamespace';

        $fixtureValue     = ['test' => true];

        $this->assertSame($this->instance , $this->instance->set($fixtureNameSpace , $fixtureValue));
        $this->assertTrue( $this->instance->has($fixtureNameSpace));
        $this->assertSame($fixtureValue , $this->instance->get($fixtureNameSpace));
        $this->assertFalse( $this->instance->has($unexistsNamespace));
        $this->assertNull($this->instance->get($unexistsNamespace));


    }

}