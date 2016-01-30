<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:49
 */

namespace CrocCrocTest\Application\Http\Stream;

class AbstractPartsStreamTest extends \PhpunitTestCase
{
    /**
     * @var \CrocCroc\Application\Http\Stream\AbstractPartsStream
     */
    protected $instance;

    public function setUp()
    {

    }

    public function toStringProvider() {

        return
            [
                [ [] , true , ''],
                [ ['<br>'] , false , ''],
                [ ['<br>' , '<b>' , 'test' , '</b>'] , false , "<br>\n<b>\ntest\n</b>"],
            ];

    }

    /**
     * @param $parts
     * @param $isReadable
     * @param $expected
     * @dataProvider toStringProvider
     */
    public function testToString($parts, $isReadable, $expected) {

        $this->instance = $this->getMockForAbstractClass('\CrocCroc\Application\Http\Stream\AbstractPartsStream');

        $this->setInaccessiblePropertyValue('isReadable' , $isReadable);
        $this->setInaccessiblePropertyValue('parts' , $parts);

        $this->assertSame($expected , (string)$this->instance);
    }

}