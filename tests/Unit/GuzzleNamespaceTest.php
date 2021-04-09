<?php

namespace Servebolt\Sdk\Tests;

use PHPUnit\Framework\TestCase;

class GuzzleNamespaceTest extends TestCase
{
    public function testThatGuzzleIsOnlyLoadedNamespacePrefixed()
    {
        $this->assertTrue(class_exists('\\ServeboltOptimizer_Vendor\\GuzzleHttp\\Client'));
        $this->assertFalse(class_exists('\\GuzzleHttp\\Client'));
    }

    public function testThatPsrIsOnlyLoadedNamespacePrefixed()
    {
        $this->assertTrue(interface_exists('\\ServeboltOptimizer_Vendor\\Psr\\Http\\Message\\MessageInterface'));
        $this->assertFalse(interface_exists('\\Psr\\Http\\Message\\MessageInterface'));
    }
}
