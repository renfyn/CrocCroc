<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 02/02/16
 * Time: 20:54
 */

namespace CrocCrocTest\Application\Http\Message;

use CrocCroc\Application\Http\Message\AbstractMessage;

class AbstractMessageTest extends \PhpunitTestCase
{
    /**
     * @var AbstractMessage;
     */
    protected $instance;

    public function setUp() {
        $this->instance = $this->getMockForAbstractClass('\CrocCroc\Application\Http\Message\AbstractMessage');
    }

    public function protocolVersionProvider() {

        return
            [
                ['1.1' , '1.0' , true , false],
                ['1.1' , '1.1' , false , false],
                ['1.1' , 'toto' , false , true],
            ];

    }

    /**
     * @param string $currentValue
     * @param string $newValue
     * @param bool $expectedNewInstance
     * @param bool $expectException
     * @dataProvider protocolVersionProvider
     */
    public function testGetWithProtocolVersion(string $currentValue ,string $newValue, bool $expectedNewInstance ,bool $expectException) {

        $this->setInaccessiblePropertyValue('protocolVersion' , $currentValue);

        if($expectException) {
            $this->setExpectedException('\InvalidArgumentException');
        }

        $instance = $this->instance->withProtocolVersion($newValue);

        if($expectedNewInstance) {
            $this->assertInstanceOf(get_class($this->instance) , $instance);
            $this->assertNotEquals($instance , $this->instance);
        } else {
            $this->assertSame($instance , $this->instance);
        }

        $this->assertSame($newValue , $instance->getProtocolVersion());

    }

    public function getHeaders() {

        $fixtureHeaders =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
            ];

        $this->setInaccessiblePropertyValue('headers' , $fixtureHeaders);
        $this->assertSame($fixtureHeaders , $this->instance->getHeaders());

    }

    public function hasHeaderProvider() {

        return
            [
                ['Host'   , true , ['example.com']],
                ['Accept' , false , []],
                ['Accept-Encoding' , true , ['gzip;q=1.0' , 'identity; q=0.5' , '*;q=0']],
            ];
    }

    /**
     * @param string $header
     * @param bool $expected
     * @param $value
     * @dataProvider hasHeaderProvider
     */
    public function testHasHeaderGet(string $header , bool $expected , $value) {
        $fixtureHeaders =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
                'Accept-Encoding' => 'gzip;q=1.0, identity; q=0.5, *;q=0',
            ];

        $this->setInaccessiblePropertyValue('headers' , $fixtureHeaders);
        $this->assertSame($expected , $this->instance->hasHeader($header));
        $this->assertSame($value , $this->instance->getHeader($header));
        if($expected) {
            $this->assertSame($fixtureHeaders[$header] , $this->instance->getHeaderLine($header));
        } else {
            $this->assertSame('' , $this->instance->getHeaderLine($header));
        }
    }

    public function testGetWithBody() {

        $oldBody = $this->getMock('\CrocCroc\Application\Http\Stream\AbstractPhpStream');

        $newBody = $this->getMock('\CrocCroc\Application\Http\Stream\AbstractPhpStream');

        $this->setInaccessiblePropertyValue('body' , $oldBody);

        $instance = $this->instance->withBody($newBody);

        $this->assertInstanceOf(get_class($this->instance) , $instance);
        $this->assertNotEquals($instance , $this->instance);

        $this->assertSame($oldBody , $this->instance->getBody());

        $this->assertSame($newBody , $instance->getBody());

        $this->assertNotEquals($this->instance->getBody() , $instance->getBody());
    }

    public function testWithoutHeader() {

        $fixtureExpected =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
            ];

        $fixtureHeaders =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
                'Accept-Encoding' => 'gzip;q=1.0, identity; q=0.5, *;q=0',
            ];

        $this->setInaccessiblePropertyValue('headers' , $fixtureHeaders);

        $fixtureName = 'Accept-Encoding';

        $instance = $this->instance->withoutHeader($fixtureName);

        $this->assertInstanceOf(get_class($this->instance) , $instance);
        $this->assertNotEquals($instance , $this->instance);

        $this->assertSame($fixtureExpected , $instance->getHeaders());

    }

    public function testWithHeader() {

        $fixtureExpected =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
                'Accept-Encoding' => 'gzip;q=1.0, identity; q=0.5, *;q=0',
            ];

        $fixtureHeaders =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',

            ];

        $this->setInaccessiblePropertyValue('headers' , $fixtureHeaders);

        $fixtureName = 'Accept-Encoding';
        $fixtureValue = 'gzip;q=1.0, identity; q=0.5, *;q=0';

        $instance = $this->instance->withHeader($fixtureName , $fixtureValue);

        $this->assertInstanceOf(get_class($this->instance) , $instance);
        $this->assertNotEquals($instance , $this->instance);

        $this->assertSame($fixtureExpected , $instance->getHeaders());

    }

    public function withAddedHeaderProvider() {

        $fixtureExpected =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
                'Accept-Encoding' => 'gzip;q=1.0, identity; q=0.5, *;q=0',
            ];

        $fixtureHeaders =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',

            ];

        $fixtureExpectedAdd =
            [
                'Host' => 'example.com',
                'Referer' => 'http://example.com/',
                'User-Agent' => 'CERN-LineMode/2.15 libwww/2.17b3',
                'Accept-Encoding' => 'gzip;q=1.0, identity; q=0.5, *;q=0, q=0',
            ];

        return [
            [$fixtureHeaders  , 'Accept-Encoding' , 'gzip;q=1.0, identity; q=0.5, *;q=0' , $fixtureExpected],
            [$fixtureExpected , 'Accept-Encoding' , 'q=0' , $fixtureExpectedAdd],

        ];

    }

    /**
     * @param array $originalHeaders
     * @param string $name
     * @param string $value
     * @param array $expectedHeaders
     * @dataProvider withAddedHeaderProvider
     */
    public function testWithAddedHeader(array $originalHeaders ,string $name ,string  $value ,array $expectedHeaders) {

        $this->setInaccessiblePropertyValue('headers' , $originalHeaders);

        $instance = $this->instance->withAddedHeader($name , $value);

        $this->assertInstanceOf(get_class($this->instance) , $instance);
        $this->assertNotEquals($instance , $this->instance);

        $this->assertSame($expectedHeaders , $instance->getHeaders());

    }

}