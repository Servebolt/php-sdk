<?php


namespace Unit;

use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Endpoints\Environment\Cache;

class CacheSdkTest extends TestCase
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function testValidUrls()
    {
        Cache::validateUrl('http://example.com/a/good/url');
        Cache::validateUrl('https://example.com/a/good/url');
        // We will handle URIs without a scheme by adding the missing https
        Cache::validateUrl('example.com/a/good/url');
        $this->assertTrue(true);
    }

    /**
     * We do not allow URIs that fail basic syntax rules.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testValidateUrlWithBadSyntax()
    {
        $url = 'http://example.com::67';
        $this->expectExceptionMessage($url . ' is not a valid URL');
        Cache::validateUrl($url);
    }

    /**
     * We do not allow ports.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testValidateUrlWithPort()
    {
        $url = 'http://example.com:67';
        $this->expectExceptionMessage($url . ' is not a valid URL');
        Cache::validateUrl($url);
    }

    /**
     * We do not allow URI fragments.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testValidateUrlWithFragment()
    {
        $url = 'http://example.com/foo#bar';
        $this->expectExceptionMessage($url . ' is not a valid URL');
        Cache::validateUrl($url);
    }

    /**
     * We do not allow IP addresses, only hostnames.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testValidateUrlWithIpAddress()
    {
        $url = 'http://127.0.0.1/foo';
        $this->expectExceptionMessage($url . ' is not a valid URL');
        Cache::validateUrl($url);
    }

    public function testSanitizeFiles()
    {
        $this->assertEquals(
            [
                'http://example.com/',
                'https://example.com/',
                'https://example.com/',
            ],
            Cache::sanitizeFiles(
                [
                    'http://example.com/',
                    'https://example.com/',
                    'example.com/',
                ]
            )
        );
    }
}
