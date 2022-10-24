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
    public function testGiveCallToArrayMethod_WhenGettingCategoryJsonString_ThenReturnArray()
    {
        // Arrange
        $expected = [
            'data'=> [
                'category_list' => [
                    [
                        "catid" => 11040766,
                        "display_name" => "女生衣著",
                    ]
                ]
            ]
        ];
        $mockHttpClient = $this->createMock(Client::class);
        $mockResponse = $this->createMock(ResponseInterface::class);
        $sut = new ShopeeWebCrawler(ExcelGenerator::class,  $mockHttpClient);
        $uri = '/api/v4/pages/get_category_tree';

        $mockHttpClient->method('request')->willReturn($mockResponse);
        $mockResponse->method('getBody')->willReturn(json_encode([
            'data'=> [
                'category_list' => [
                    [
                        "catid" => 11040766,
                        "display_name" => "女生衣著",
                    ]
                ]
            ]
        ]));

        // Assert
        $actual = $sut->getCategory()->toArray();

        // Act
        $this->assertEquals($expected, $actual);
    }
}
