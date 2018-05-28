<?php

namespace Mingalevme\Tests\Utils\Url;

use PHPUnit\Framework\TestCase;
use Mingalevme\Utils\Url;

class UrlTest extends TestCase
{
    /**
     * Just integration test, for unit test
     * @see https://github.com/mingalevme/phputils-secure-link/blob/master/tests/SecureLinkTest.php
     */
    public function testSecureLink()
    {
        $signer = new Url\SecureLink('phpunit');

        $this->assertEquals('https://github.com/mingalevme/phputils-url?signature=UG_RvLVM5QmPfuKHj9hNtw%3D%3D',
            $signer->sign('https://github.com/mingalevme/phputils-url'));
    }

    /**
     * Just integration test, for unit test
     * @see https://github.com/mingalevme/http-build-url/blob/master/tests/HttpBuildUrlTest.php
     */
    public function testBuild()
    {
        $this->assertEquals('https://github.com/mingalevme/phputils-url', Url::build('/mingalevme/phputils-url', [
            's' => 'https',
            'h' => 'github.com',
        ]));
    }

    public function testParseQueryString()
    {
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
        ], Url::parseQueryString('foo=bar&bar=foo'));
    }

    public function testParseQueryStringFromUrl()
    {
        $this->assertEquals([], Url::parseQueryStringFromUrl('http://example.com'));

        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'foo',
        ], Url::parseQueryStringFromUrl('http://example.com?foo=bar&bar=foo'));
    }

    public function testAbsolutizeUrl1()
    {
        $this->assertEquals('https://github.com/mingalevme/phputils-url',
            Url::absolutizeUrl('/mingalevme/phputils-url', 'https://github.com'));

    }

    public function testAbsolutizeUrl2()
    {
        try {
            Url::absolutizeUrl('/mingalevme/phputils-url', '/mingalevme');
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail('Exception has not been thrown');
    }

    public function testIsAbsolute()
    {
        $this->assertTrue(Url::isAbsolute('https://github.com/mingalevme/phputils-url'));
        $this->assertFalse(Url::isAbsolute('//github.com/mingalevme/phputils-url'));
        $this->assertFalse(Url::isAbsolute('/mingalevme/phputils-url'));
    }

    public function testIsRelative()
    {
        $this->assertFalse(Url::isRelative('https://github.com/mingalevme/phputils-url'));
        $this->assertTrue(Url::isRelative('//github.com/mingalevme/phputils-url'));
        $this->assertTrue(Url::isRelative('/mingalevme/phputils-url'));
    }

    public function testIsLocal()
    {
        $this->assertTrue(Url::isLocal('/mingalevme/phputils-url'));
        $this->assertTrue(Url::isLocal('file:///mingalevme/phputils-url'));
        $this->assertFalse(Url::isLocal('https://github.com/mingalevme/phputils-url'));
        $this->assertFalse(Url::isLocal('//github.com/mingalevme/phputils-url'));
    }
}
