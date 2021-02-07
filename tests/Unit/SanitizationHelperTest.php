<?php

namespace Servebolt\SDK\Tests;

use Servebolt\SDK\Helpers as Helpers;
use PHPUnit\Framework\TestCase;
use Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException;

class SanitizationHelperTest extends TestCase
{

    private array $validUrls = [
        'https://username:password@example.com/path/to/something?query=string&with=multiple-parts#hashtag-something',
        'http://example.com/path/to/something?query=string&with=multiple-parts#hashtag-something',
    ];

    private array $urlsNeededToBeSanitized = [
        'http://example.com/path/t�/something?query=string&with=multiple-parts#hashtag-something',
        'http://example.c�m/path/to/something?query=string&with=multiple-parts#hashtag-something',
    ];

    public function testUrlSanitizationWithValidUrls()
    {
        foreach ($this->validUrls as $url) {
            try {
                $this->assertEquals(Helpers\sanitizeUrl($url), $url);
            } catch (ServeboltUrlWasSanitizedException $exception) {
                $this->fail(sprintf('URL "%s" threw exception "%s".', $url, 'ServeboltUrlWasSanitizedException'));
            }
        }
    }

    public function testUrlsSanitizationWithoutExceptionHandlingWithUrlsNeededToBeSanitized()
    {
        $this->assertNotEquals(
            $this->urlsNeededToBeSanitized,
            Helpers\sanitizeUrls($this->urlsNeededToBeSanitized, false),
            'Failed asserting that function sanitizeUrls changed the array of URLs after sanitization.'
        );
    }

    public function testUrlSanitizationWithoutExceptionHandlingWithUrlsNeededToBeSanitized()
    {
        foreach ($this->urlsNeededToBeSanitized as $url) {
            $failMessage = sprintf(
                'Failed asserting that URL "%s" (that needed to be sanitized) changed after sanitization.',
                $url
            );
            $this->assertNotEquals(
                $url,
                Helpers\sanitizeUrl($url, false),
                $failMessage
            );
        }
    }

    public function testUrlsSanitizationWithExceptionHandlingWithUrlsNeededToBeSanitized()
    {
        $this->expectException(ServeboltUrlWasSanitizedException::class);
        try {
            Helpers\sanitizeUrls($this->urlsNeededToBeSanitized);
            $failMessage = sprintf(
                'The function sanitizeUrls did not throw exception "%s".',
                'ServeboltUrlWasSanitizedException'
            );
            $this->fail($failMessage);
        } catch (ServeboltUrlWasSanitizedException $exception) {
            throw $exception; // Re-throw exception for PHPUnit to detect
        }
    }

    public function testUrlSanitizationWithExceptionHandlingWithUrlsNeededToBeSanitized()
    {
        foreach ($this->urlsNeededToBeSanitized as $url) {
            $this->expectException(ServeboltUrlWasSanitizedException::class);
            try {
                Helpers\sanitizeUrl($url);
                $failMessage = sprintf(
                    'URL "%s" did not throw exception "%s".',
                    $url,
                    'ServeboltUrlWasSanitizedException'
                );
                $this->fail($failMessage);
            } catch (ServeboltUrlWasSanitizedException $exception) {
                throw $exception; // Re-throw exception for PHPUnit to detect
            }
        }
    }

    /*
    public function testDomainWithPathSanitization()
    {

    }

    public function testUrlsSanitization()
    {

    }

    public function testDomainsWithPathSanitization()
    {

    }
    */
}
