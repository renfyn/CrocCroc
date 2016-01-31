<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:49
 */

namespace CrocCrocTest\Application\Http\Stream;

class AbstractPhpStreamTest extends \PhpunitTestCase
{
    /**
     * @var \CrocCroc\Application\Http\Stream\AbstractPhpStream
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = $this->getMockForAbstractClass('\CrocCroc\Application\Http\Stream\AbstractPhpStream');
    }

    public function testConstructor() {

        $this->instance = $this->getMockForAbstractClass('\CrocCroc\Application\Http\Stream\AbstractPhpStream');

        $this->assertTrue(is_resource($this->getInaccessiblePropertyValue('resource')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isReadable')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isWritable')));
        $this->assertTrue(is_bool($this->getInaccessiblePropertyValue('isSeekable')));

    }

    public function toStringProvider() {
        $fixtureStreamContents = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,';

        return
            [
                [$fixtureStreamContents , true  , $fixtureStreamContents],
                [$fixtureStreamContents , false , ''],
            ];

    }

    /**
     * @param $fixtureStreamContents
     * @param $isReadable
     * @param $expected
     * @dataProvider toStringProvider
     */
    public function testToString(string $fixtureStreamContents ,bool $isReadable , string $expected)
    {
        $this->setInaccessiblePropertyValue('isReadable' , $isReadable);
        $fp = $this->getInaccessiblePropertyValue('resource');

        fputs($fp , $fixtureStreamContents);

        $this->assertSame($expected , (string)$this->instance);

    }

    public function testClose() {

        $this->instance->close();
        $this->assertFalse(is_resource($this->getInaccessiblePropertyValue('resource')));

    }

    public function testDetach() {
        $fp = $this->getInaccessiblePropertyValue('resource');
        $this->assertSame($fp , $this->instance->detach());
        $this->assertNull($this->getInaccessiblePropertyValue('resource'));

    }

    public function testGetSize()
    {
        $fixtureTest = 'Lorem ipsum dolor sit amet';
        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $fixtureTest);

        $this->assertSame(strlen($fixtureTest) , $this->instance->getSize());

        $this->setInaccessiblePropertyValue('resource' , null);
        $this->assertNull($this->instance->getSize());
    }

    public function testTell() {

        $fixtureTest = 'Lorem ipsum dolor sit amet';
        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $fixtureTest);

        $this->assertSame(ftell($fp) , $this->instance->tell());
    }

    public function testEof() {

        $fixtureTest = 'Lorem ipsum dolor sit amet';
        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $fixtureTest);

        $this->assertSame(feof($fp) , $this->instance->eof());

    }

    public function testIsSeekable() {

        $fixtureSeekable = true;

        $this->setInaccessiblePropertyValue('isSeekable' , $fixtureSeekable);

        $this->assertTrue($this->instance->isSeekable());

    }

    public function seekProvider() {

        return [
           [1 , SEEK_SET, false,],
           ['toto' , SEEK_CUR , true],
        ];

    }

    public function testSeek($offset , $mode , $exceptionExpected) {

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
        }

        $fixtureTest = 'Lorem ipsum dolor sit amet';
        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $fixtureTest);

        $this->assertSame(fseek($fp , $offset , $mode) , $this->instance->eof());

    }
}