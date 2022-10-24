<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Zero\ShopeeWebCrawler;
use Zero\ExcelGenerator;

final class ShopeeProductTest extends TestCase
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
    public function testGiveParameters_WhenGettingProduct_ThenCallGiveRequest()
    {
        // Arrange
        $mockHttpClient = $this->createMock(Client::class);
        $sut = new ShopeeWebCrawler(ExcelGenerator::class,  $mockHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        // Assert
        $mockHttpClient->expects($this->once())->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']]);

        // Act
        $sut->getProduct($keyword, $limit, $newest);
    }

    /**
     * @test
     */
    public function testGiveCallGettingBody_WhenGettingProduct_ThenReturnJsonString()
    {
        // Arrange
        $expected = 'json string';
        $stubHttpClient = $this->getMockBuilder(Client::class)->getMock();
        $mockResponse = $this->createMock(ResponseInterface::class);
        $sut = new ShopeeWebCrawler(ExcelGenerator::class,  $stubHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        $stubHttpClient->method('request')->willReturn($mockResponse);
        $mockResponse->method('getBody')->willReturn('json string');

        // Act 
        $sut->getProduct($keyword, $limit, $newest);
        $actual = $sut->getBody();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testGiveCallToArrayMethod_WhenGettingProductJsonString_ThenReturnArray()
    {
        // Arrange
        $expected = [
            'items'=> [
                'item_basic' => [
                    [
                        "itemid" => 6840261731,
                        "name" => "PTCG 中文版 寶可夢卡牌【普卡一張一元】 100張以內無重複出貨~",
                    ]
                ]
            ]
        ];
        $stubHttpClient = $this->getMockBuilder(Client::class)->getMock();
        $mockResponse = $this->createMock(ResponseInterface::class);
        $sut = new ShopeeWebCrawler(ExcelGenerator::class,  $stubHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        $stubHttpClient->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']])->willReturn($mockResponse);
        $mockResponse->method('getBody')->willReturn(json_encode([
            'items'=> [
                'item_basic' => [
                    [
                        "itemid" => 6840261731,
                        "name" => "PTCG 中文版 寶可夢卡牌【普卡一張一元】 100張以內無重複出貨~",
                    ]
                ]
            ]
        ]));

        // Act 
        $actual = $sut->getProduct($keyword, $limit, $newest)->toArray();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testGiveCallGettingRequest_WhenGettingProduct_ThenErrorException()
    {
        // Arrange
        $mockHttpClient = $this->createMock(Client::class);
        $sut = new ShopeeWebCrawler(ExcelGenerator::class,  $mockHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        // Assert
        $this->expectException(ErrorException::class);

        // Act
        $mockHttpClient->expects($this->once())->method('request')->will($this->throwException(new ErrorException('Request error.')));
        $sut->getProduct($keyword, $limit, $newest);
    }


    /**
     * @test
     */
    public function testGiveKeyword_WhenGettingProduct_ThenErrorException()
    {
        // Arrange
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $sut = $this->createMock(ShopeeWebCrawler::class);
        $sut->expects($this->any())->method('getProduct')->with($keyword, $limit, $newest)->will($this->throwException(new ErrorException('Request error.')));

        // Assert
        $this->expectException(ErrorException::class);

        // Act 
        $sut->getProduct($keyword, $limit, $newest);
    }
}
