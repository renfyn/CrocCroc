<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 24/01/16
 * Time: 20:58
 */

namespace CrocCrocTest\Application\Http;

use CrocCroc\Application\Http\Uri;

class UriTest extends \PhpunitTestCase
{

    /**
     * @var \CrocCroc\Application\Http\Uri
     */
    protected $instance;

    public function setUp() {

        $this->instance = new Uri();

    }

    public function testConstructor() {

        $fixtureParams = [
            'scheme' => 'http',
            'host'   => 'toto.com',
            'port'   => 80,
            'query'  => ['test' => 'test']
        ];

        $fixtureUri = 'http://toto.com:80?test=test';

        $this->instance = new Uri($fixtureUri);

        $this->assertSame($fixtureParams['scheme'] , $this->getInaccessiblePropertyValue('scheme'));
        $this->assertSame($fixtureParams['host'] , $this->getInaccessiblePropertyValue('host'));
        $this->assertSame($fixtureParams['port'] , $this->getInaccessiblePropertyValue('port'));
        $this->assertSame($fixtureParams['query'] , $this->getInaccessiblePropertyValue('query'));

    }

    public function testInvalidConstructor() {
        $this->setExpectedException('\InvalidArgumentException');
        $this->instance = new Uri('bla bla bla');



    }

    public function schemeProvider() {

        return
            [
                ['http' , 'http'  , 'http'  , false , false],
                ['http' , 'https' , 'https' , true  , false],
                ['http' , 'HTTPS' , 'https' , true  , false],
                ['http' , 'ftp'   , 'http'  , true  , true],
            ];

    }

    /**
     * @dataProvider schemeProvider
     * @param string $originalScheme
     * @param string $scheme
     * @param string $expectedValue
     * @param bool $expectNewInstance
     * @param bool $expectException
     */
    public function testWithGetScheme(string $originalScheme , string $scheme, string $expectedValue , bool $expectNewInstance , bool $expectException) {

        $this->setInaccessiblePropertyValue('scheme' , $originalScheme);

        if($expectException) {
            $this->setExpectedException('\InvalidArgumentException');
        }

        $newInstance = $this->instance->withScheme($scheme);

        if($expectNewInstance) {
            $this->assertInstanceOf(get_class($this->instance) , $newInstance);
            $this->assertNotSame($newInstance , $this->instance);
            $this->assertSame($originalScheme , $this->instance->getScheme());
        } else {
            $this->assertSame($this->instance , $newInstance);
        }
        $this->assertSame($expectedValue , $newInstance->getScheme());

    }

    public function authorityProvider() {

        return
            [
                [null , 'test.local.com' , ''     , ''     , ''          ,'test.local.com:80'],
                [80   , 'test.local.com' , ''     , ''     , ''          ,'test.local.com:80'],
                [8080 , 'test.local.com' , 'user' , ''     , 'user'      ,'user@test.local.com:8080'],
                [8888 , 'test.local.com' , 'user' , 'pass' , 'user:pass' ,'user:pass@test.local.com:8888'],
            ];

    }

    /**
     * @param $port
     * @param string $host
     * @param string $userName
     * @param string $userPassWord
     * @param string $userInfo
     * @param $expectedValue
     * @dataProvider authorityProvider
     */
    public function testGetAuthority($port , string $host , string $userName , string $userPassWord , string $userInfo , $expectedValue) {

        $this->instance = $this->getMock(get_class($this->instance) , ['getUserInfo']);

        $this->instance->expects($this->once())
                        ->method('getUserInfo')
                        ->willReturn($userInfo);

        $this->setInaccessiblePropertyValue('port' , $port);
        $this->setInaccessiblePropertyValue('host' , $host);
        $this->setInaccessiblePropertyValue('user' , $userName);
        $this->setInaccessiblePropertyValue('pass' , $userPassWord);

        $this->assertSame($expectedValue , $this->instance->getAuthority());

    }

    public function userInfoProvider() {

        return
            [
                [''     , ''     , ''          , ''     , ''     , ''      , false],
                [''     , ''     , ''          , 'user' , null   , 'user'      , true],
                ['user' , 'pass' , 'user:pass' , 'user' , 'pass' , 'user:pass'      , false],
                ['user' , null   , 'user'      , 'user' , 'pass' , 'user:pass' , true],
            ];

    }

    /**
     * @param string $originalUser
     * @param string $originalPassWord
     * @param string $originalScheme
     * @param string $user
     * @param string $passWord
     * @param string $expectedValue
     * @param bool $expectNewInstance
     * @dataProvider userInfoProvider
     */
    public function testWithGetUserInfo(string $originalUser , $originalPassWord , string $originalScheme , string $user , $passWord ,string $expectedValue , bool $expectNewInstance) {

        $this->setInaccessiblePropertyValue('user' , $originalUser);
        $this->setInaccessiblePropertyValue('pass' , $originalPassWord);

        $instance = $this->instance->withUserInfo($user , $passWord);

        if($expectNewInstance) {
            $this->assertInstanceOf(get_class($this->instance) , $instance);
            $this->assertNotSame($instance , $this->instance);
            $this->assertSame($originalScheme , $this->instance->getUserInfo());
        } else {
            $this->assertSame($this->instance , $instance);
        }
        $this->assertSame($expectedValue , $instance->getUserInfo());

    }

    public function portProvider() {

        return
            [
                [null  , 80],
                [ 8080 , 8080],
            ];

    }

    /**
     * @param $port
     * @param int $expectedPort
     * @dataProvider portProvider
     */
    public function testGetPort($port , int $expectedPort) {

        $this->setInaccessiblePropertyValue('port' , $port);

        $this->assertSame($expectedPort , $this->instance->getPort());
    }

    /**
     * test getHost
     */
    public function testGetHost() {

        $fixtureHost = 'host.local.com';

        $this->setInaccessiblePropertyValue('host' , $fixtureHost);

        $this->assertSame($fixtureHost , $this->instance->getHost());

    }

    /**
     * test getPath
     */
    public function testGetPath() {

        $fixturePath = '/toto.html';

        $this->setInaccessiblePropertyValue('path' , $fixturePath);

        $this->assertSame($fixturePath , $this->instance->getPath());

    }

    /**
     * test getQuery
     */
    public function testGetQuery() {

        $fixtureQuery =
            [
                'param1' => 'value1',
                'param2' => 'value2',
                'param3' => 'test value',
            ];

        $expectedQuery = 'param1=value1&param2=value2&param3=test%20value';

        $this->setInaccessiblePropertyValue('query' , $fixtureQuery);

        $this->assertSame($expectedQuery , $this->instance->getQuery());

    }

    /**
     * test getHost
     */
    public function testGetFragment() {

        $fixtureFragment = 'toto';

        $this->setInaccessiblePropertyValue('fragment' , $fixtureFragment);

        $this->assertSame($fixtureFragment , $this->instance->getFragment());

    }



    public function withProvider()   {
        return
        [
            ['port'     , 80             , 8080                        , true   , false ],
            ['port'     , 80             , 'toto'                      , false  , true  ],
            ['port'     , 80             , 10000000001                 , false  , true  ],
            ['port'     , 8080           , 8080                        , false  , false ],
            ['port'     , null           , 8080                        , true   , false ],
            ['host'     , null           , 'a'                         , true   ,  false ],
            ['host'     , null           , '0'                         , true   ,  false ],
            ['host'     , null           , 'a.b'                       , true   ,  false ],
            ['host'     , null           , 'localhost'                 , true   ,  false ],
            ['host'     , null           , 'google.com'                , true   ,  false ],
            ['host'     , null           , 'news.google.co.uk'         , true   ,  false ],
            ['host'     , null           , 'xn--fsqu00a.xn--0zwm56d'   , true   ,  false ],
            ['host'     , null           , 'goo gle.com'               , true   ,  true  ],
            ['host'     , null           , 'google..com'               , true   ,  true  ],
            ['host'     , null           , 'google.com '               , true   ,  true  ],
            ['host'     , null           , 'google-.com'               , true   ,  true  ],
            ['host'     , null           , '.google.com'               , true   ,  true  ],
            ['host'     , null           , '<script'                   , true   ,  true  ],
            ['host'     , null           , 'alert('                    , true   ,  true  ],
            ['host'     , null           , '.'                         , true   ,  true  ],
            ['host'     , null           , ''                          , true   ,  true  ],
            ['host'     , null           , ''                          , true   ,  true  ],
            ['host'     , null           , '-'                         , true   ,  true  ],
            ['host'     , null           , '55.25.0.12'                , true   ,  false ],
            ['host'     , null           , '55.25.0=12'                , true   ,  true  ],
            ['host'     , 'croccroc.com' , 'croccroc.com'              , false  ,  false ],
            ['path'     , null           , '/'                         , true   ,  false ],
            ['path'     , '/'            , '/'                         , false  ,  false ],
            ['path'     , '/test'        , 'test'                      , false  ,  true  ],
            ['path'     , '/'            , '/'                         , false  ,  false ],
            ['fragment' , 'test'         , 'test'                      , false  ,  false ],
            ['fragment' , ''             , 'test'                      , true  ,  false ],
        ];
    }

    /**
     * @param string $property
     * @param $original
     * @param $newValue
     * @param bool $expectNewInstance
     * @param bool $expectException
     * @dataProvider withProvider
     * @group uriWith
     */
    public function testWithAny(string $property , $original  , $newValue , bool $expectNewInstance, bool $expectException) {

        $this->setInaccessiblePropertyValue($property, $original);

        $get = 'get' . ucfirst($property);
        $set = 'with' . ucfirst($property);

        if($expectException) {
            $this->setExpectedException('\InvalidArgumentException');
        }

        $instance = $this->instance->$set($newValue);

        if($expectNewInstance) {
            $this->assertInstanceOf(get_class($this->instance) , $instance);
            $this->assertNotSame($instance , $this->instance);
            $this->assertSame($original , $this->getInaccessiblePropertyValue($property));
        } else {
            $this->assertSame($this->instance , $instance);
        }
        $this->assertSame($newValue , $instance->$get());

    }

    public function withQueryProvider() {

        return
            [
                ['test=test' , 'test=test'                           , false , false],
                [''          , '?test=/test&toto=toto&tata=do%20re'  , false  , true],
                [''          , 'test=test&toto=toto&tata=do%20re'    , true , false],
                ['test=test' , true                                  , false , true],

            ];

    }

    /**
     * @param $original
     * @param $newValue
     * @param bool $expectNewInstance
     * @param bool $expectException
     * @dataProvider withQueryProvider
     */
    public function testWithQuery($original  , $newValue , bool $expectNewInstance, bool $expectException) {

        $property = 'query';

        parse_str($original , $queryArray );

        $this->setInaccessiblePropertyValue($property, $queryArray);


        if($expectException) {
            $this->setExpectedException('\InvalidArgumentException');
        }

        $instance = $this->instance->withQuery($newValue);

        if($expectNewInstance) {
            $this->assertInstanceOf(get_class($this->instance) , $instance);
            $this->assertNotSame($instance , $this->instance);
            $this->assertSame($original , http_build_query($this->getInaccessiblePropertyValue($property) , '', '&' , PHP_QUERY_RFC3986));
        } else {

            $this->assertSame($this->instance , $instance);
        }

        $this->assertSame($newValue , $instance->getQuery());
    }

    public function uriProvider() {

        return [
            ['http://test.com'],
            ['http://test.com:8080'],
            ['http://test.com:8585/test'],
            ['http://test.com:8585/test?query=test'],
            ['http://test.com#fragment'],
            ['http://username:password@hostname:9090/path?arg=value#anchor'],
        ];

    }

    /**
     * @param $uriString
     * @dataProvider uriProvider
     */
    public function testToString($uriString) {

        $this->instance = new Uri($uriString);

        $uri = (string)$this->instance;

        $this->assertSame($uriString , $uri);

    }

}