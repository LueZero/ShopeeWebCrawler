<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Zero\ShopeeWebCrawler;
use Zero\ExcelGenerator;

final class ShopeeCategoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function test()
    {
        // Arrange
        
        // Act
        
        // Assert
        $this->assertTrue(true);
    }
}
