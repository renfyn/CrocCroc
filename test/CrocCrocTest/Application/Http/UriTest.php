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
        $this->setInaccessiblePropertyValue('userName' , $userName);
        $this->setInaccessiblePropertyValue('userPass' , $userPassWord);

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

        $this->setInaccessiblePropertyValue('userName' , $originalUser);
        $this->setInaccessiblePropertyValue('userPass' , $originalPassWord);

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

}