<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use BigGo\InterviewQuestion\ShopeeProductWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;

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
        $sut = new ShopeeProductWebCrawler(ExcelGenerator::class,  $mockHttpClient);
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
    public function testGiveCallGettingBody_WhenGettingProduct_ThenReturnJson()
    {
        // Arrange
        $expected = 'json string';
        $stubHttpClient = $this->getMockBuilder(Client::class)->getMock();
        $mockResponse = $this->createMock(ResponseInterface::class);
        $sut = new ShopeeProductWebCrawler(ExcelGenerator::class,  $stubHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        $stubHttpClient->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']])->willReturn($mockResponse);
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
    // public function testGiveCallArray_WhenGettingProduct_ThenReturnArray()
    // {
    //     // Arrange
    //     $expected = 'json string';
    //     $stubHttpClient = $this->getMockBuilder(Client::class)->getMock();
    //     $mockResponse = $this->createMock(ResponseInterface::class);
    //     $sut = new ShopeeProductWebCrawler(ExcelGenerator::class,  $stubHttpClient);
    //     $keyword = 'test';
    //     $limit = 60;
    //     $newest = 0;
    //     $by = 'relevancy';
    //     $order = 'desc';
    //     $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

    //     $stubHttpClient->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']])->willReturn($mockResponse);
    //     $mockResponse->method('getBody')->willReturn('json string');

    //     // Act 
    //     $actual = $sut->getProduct($keyword, $limit, $newest);

    //     // Assert
    //     $this->assertEquals($expected, $actual);
    // }


    /**
     * @test
     */
    public function testGiveCallGettingRequest_WhenGettingProduct_ThenErrorException()
    {
        // Arrange
        $mockHttpClient = $this->createMock(Client::class);
        $sut = new ShopeeProductWebCrawler(ExcelGenerator::class,  $mockHttpClient);
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
        $sut = $this->createMock(ShopeeProductWebCrawler::class);
        $sut->expects($this->any())->method('getProduct')->with($keyword, $limit, $newest)->will($this->throwException(new ErrorException('Request error.')));

        // Assert
        $this->expectException(ErrorException::class);

        // Act 
        $sut->getProduct($keyword, $limit, $newest);
    }
}
