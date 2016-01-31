<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:49
 */

namespace CrocCrocTest\Application\Http\Stream;

use org\bovigo\vfs\vfsStream;

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
           [1 , SEEK_SET , false],
           [-1 , SEEK_CUR , true],
        ];

    }

    /**
     * @dataProvider seekProvider
     * @param $offset
     * @param $mode
     * @param $exceptionExpected
     */
    public function testSeek($offset , $mode , $exceptionExpected) {

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
            $this->setInaccessiblePropertyValue('resource' , null);
        } else {
            vfsStream::setup('/var' , 0777);
            $uri = vfsStream::url('var/file.txt');
            $fp = fopen($uri , 'a+');
            $this->setInaccessiblePropertyValue('resource' , $fp);
            $fixtureTest = 'Lorem ipsum dolor sit amet';
            $fp = $this->getInaccessiblePropertyValue('resource');
            fputs($fp , $fixtureTest);
            fseek($fp , $offset , $mode);
        }



       $this->instance->seek($offset , $mode);

        $this->assertSame( $fp, $this->getInaccessiblePropertyValue('resource'));

    }


    public function testSeekEnable() {

        vfsStream::setup('var' , 0777);
        $url    = vfsStream::url('var/test.txt' );

        $fp     = fopen($url , 'w');
        fwrite($fp , 'test test test test test');
        $this->setInaccessiblePropertyValue('resource' , $fp);
        $this->setInaccessiblePropertyValue('isSeekable' , true);
        $this->instance->seek(10);

        $this->assertSame( $fp, $this->getInaccessiblePropertyValue('resource'));

    }

    public function rewindProvider() {

        return [
            [false],
            [true],
        ];

    }

    /**
     * @dataProvider rewindProvider
     * @param $offset
     * @param $mode
     * @param $exceptionExpected
     */
    public function testRewind($exceptionExpected) {

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
            $this->setInaccessiblePropertyValue('resource' , null);
        } else {
            vfsStream::setup('/var' , 0777);
            $uri = vfsStream::url('var/file.txt');
            $fp = fopen($uri , 'a+');
            $this->setInaccessiblePropertyValue('resource' , $fp);
            $fixtureTest = 'Lorem ipsum dolor sit amet';
            $fp = $this->getInaccessiblePropertyValue('resource');
            fputs($fp , $fixtureTest);
            rewind($fp);
        }


        $this->instance->rewind();

        $this->assertSame( $fp, $this->getInaccessiblePropertyValue('resource'));

    }

    public function testIsWritable()
    {
        $fixtureWritable = true;

        $this->setInaccessiblePropertyValue('isWritable' , $fixtureWritable);

        $this->assertTrue($this->instance->isWritable());
    }

    public function writeProvider() {
        return
            [
                ['test' , true , false],
                ['test' , false, true],
            ];
    }

    /**
     * @param string $string
     * @param bool $isWritable
     * @param bool $exceptionExpected
     * @dataProvider writeProvider
     */
    public function testWrite(string $string ,bool $isWritable , bool $exceptionExpected) {


        $this->setInaccessiblePropertyValue('isWritable' , $isWritable);

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
        }

        $this->assertSame(strlen($string) , $this->instance->write($string));

        $fp = $this->getInaccessiblePropertyValue('resource');

        $this->assertSame($string ,  stream_get_contents($fp , -1 , 0));

    }

    public function testIsReadable()
    {
        $fixtureReadable = true;

        $this->setInaccessiblePropertyValue('isReadable' , $fixtureReadable);

        $this->assertTrue($this->instance->isReadable());
    }

    public function readProvider() {
        return
            [
                ['test test test' , 5  , 'test '      , true  , false],
                ['test test'      , 4  , 'test'       , true  , false],
                ['test test '     , -1 , 'test test ' , true  , true],
                ['test test'      , 4  , 'test '      , false , true],
            ];
    }

    /**
     * @param string $string
     * @param int $length
     * @param string $expected
     * @param bool $isReadable
     * @param bool $exceptionExpected
     * @dataProvider readProvider
     */
    public function testRead(string $string ,int $length , string $expected  ,bool $isReadable , bool $exceptionExpected) {

        $this->setInaccessiblePropertyValue('isReadable' , $isReadable);
        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $string);
        fseek($fp , 0);

        $this->setInaccessiblePropertyValue('resource' , $fp);

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
        }

        $this->assertSame($expected , $this->instance->read($length));

    }

    public function getContentsProvider() {

        return
        [
            ['test' , true  , false],
            ['test' , false , true],
            [ null  , false , true],

        ];

    }

    /**
     * @dataProvider getContentsProvider
     * @param $contents
     * @param bool $isReadable
     * @param bool $exceptionExpected
     */
    public function testGetContents($contents , bool $isReadable , bool $exceptionExpected ) {

        if($exceptionExpected) {
            $this->setExpectedException('\RuntimeException');
        }

        $fp = $this->getInaccessiblePropertyValue('resource');
        fputs($fp , $contents);


        $this->setInaccessiblePropertyValue('isReadable' , $isReadable);

        $this->assertSame($contents , $this->instance->getContents());
    }

    /**

     */
    public function testFailedGetContents() {


        $this->setExpectedException('\RuntimeException');
        $this->setInaccessiblePropertyValue('isReadable' , true);

        $this->setInaccessiblePropertyValue('resource' , null);

        $this->instance->getContents();
    }

    public function getMetadataProvider() {

        return
            [
                ['uri'   , 'php://memory'],
                [ null   , 'all'],
                [ 'toto' , null],
            ];
    }

    /**
     * @param $key
     * @param $expected
     * @dataProvider getMetadataProvider
     */
    public function testGetMetadata($key , $expected) {

        if($expected === 'all') {
            $fp = $this->getInaccessiblePropertyValue('resource');
            $expected = stream_get_meta_data($fp);
        }

        $this->assertSame($expected , $this->instance->getMetadata($key));

    }

}