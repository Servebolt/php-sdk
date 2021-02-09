<?php

namespace Servebolt\SDK\Tests;

use Servebolt\SDK\Helpers as Helpers;
use PHPUnit\Framework\TestCase;
use Servebolt\SDK\Exceptions\ServeboltDomainWithPathWasSanitizedException;

class DomainSanitizationHelperTest extends TestCase
{

    private array $validDomains = [
        'something.com',
        'something.com/path/to/somewhere',
        'something.com/path/to/somewhere/',
    ];

    private array $invalidDomains = [
        'someth�ng.com',
        'something.c�m/path/to/somewhere',
    ];

    public function testDomainWithPathSanitizationWithValid()
    {
        foreach ($this->validDomains as $domain) {
            try {
                $this->assertEquals(Helpers\sanitizeDomainWithPath($domain), $domain);
            } catch (ServeboltDomainWithPathWasSanitizedException $exception) {
                $this->fail(sprintf(
                    'Domain "%s" threw exception "%s".',
                    $domain,
                    'ServeboltDomainWithPathWasSanitizedException'
                ));
            }
        }
        /*
        foreach ($this->validDomains as $domain) {
            $this->assertEquals(Helpers\sanitizeDomainWithPath($domain), $domain);
        }
        */
    }

    public function testDomainsWithPathSanitization()
    {
        $this->assertEquals(Helpers\sanitizeDomainsWithPath($this->validDomains), $this->validDomains);
    }
}
