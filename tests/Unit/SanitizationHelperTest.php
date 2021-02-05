<?php

namespace Servebolt\SDK\Tests;

use Servebolt\SDK\Helpers as Helpers;
use PHPUnit\Framework\TestCase;
use Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException;
//use Servebolt\SDK\Exceptions\ServeboltInvalidUrlException;

class SanitizationHelperTest extends TestCase
{

    public function testUrlSanitizationWithValidUrls()
    {
        $validUrls = [
            'https://username:password@example.com/path/to/something?query=string&with=multiple-parts#hashtag-something',
            'http://example.com/path/to/something?query=string&with=multiple-parts#hashtag-something',
        ];
        foreach ($validUrls as $validUrl) {
            $this->assertEquals(Helpers\sanitizeUrl($validUrl), $validUrl);
        }
    }

    /*
    public function testUrlSanitizationWithInvalidUrls()
    {
        $invalidUrls = [
            'ssh://example.com/path/to/something?query=string&with=multiple-parts#hashtag-something',
            'not-a-url',
            'mail@example.com'
        ];
        $this->expectException(ServeboltInvalidUrlException::class);
        foreach ($invalidUrls as $invalidUrl) {
            try {
                Helpers\sanitizeUrl($invalidUrl);
                $this->fail(sprintf('URL "%s" did not throw exception "%s".', $invalidUrl, 'ServeboltInvalidUrlException'));
            } catch (ServeboltInvalidUrlException $exception) {
                throw $exception; // Re-throw exception to PHPUnit
            }
        }
    }
    */

    public function testUrlSanitizationWithUrlsNeededToBeSanitized()
    {
        $invalidUrls = [
            'http://example.com/path/t�/something?query=string&with=multiple-parts#hashtag-something',
        ];
        $this->expectException(ServeboltUrlWasSanitizedException::class);
        foreach ($invalidUrls as $invalidUrl) {
            var_dump(Helpers\sanitizeUrl($invalidUrl));
            try {
                var_dump(Helpers\sanitizeUrl($invalidUrl));
                $this->fail(sprintf('URL "%s" did not throw exception "%s".', $invalidUrl, 'ServeboltUrlWasSanitizedException'));
            } catch (ServeboltUrlWasSanitizedException $exception) {
                throw $exception; // Re-throw exception to PHPUnit
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
