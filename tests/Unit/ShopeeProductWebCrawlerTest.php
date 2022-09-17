<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use BigGo\InterviewQuestion\ShopeeProductWebCrawler;
use BigGo\InterviewQuestion\ExcelGenerator;

final class ShopeeProductWebCrawlerTest extends TestCase
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
    public function testGiveRequest_WhenGettingProduct_ThenAssertParameters()
    {
        // Arrange
        $excelGenerator = new ExcelGenerator();
        $mockHttpClient = $this->createMock(Client::class);
        $stub = new ShopeeProductWebCrawler($excelGenerator,  $mockHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        // Assert
        $mockHttpClient->expects($this->once())->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']]);

        // Act
        $stub->getProduct($keyword, $limit, $newest);
    }

    /**
     * @test
     */
    public function testGiveGettingBody_WhenGettingProduct_ThenJsonString()
    {
        // Arrange
        $excelGenerator = new ExcelGenerator();
        $mockHttpClient = $this->createMock(Client::class);
        $mockResponse = $this->createMock(ResponseInterface::class);
        $stub = new ShopeeProductWebCrawler($excelGenerator,  $mockHttpClient);
        $keyword = 'test';
        $limit = 60;
        $newest = 0;
        $by = 'relevancy';
        $order = 'desc';
        $uri = '/api/v4/search/search_items?by=' . $by . '&keyword=' . $keyword . '&limit=' . $limit . '&newest=' . $newest . '&order=' . $order . '&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';

        $mockHttpClient->method('request')->with('GET', $uri, ['headers' => ['x-api-source' => 'pc']])->willReturn($mockResponse);
        $mockResponse->method('getBody')->willReturn('json string');

        // Act 
        $stub->getProduct($keyword, $limit, $newest);
        $actual = $stub->getBody();

        // Assert
        $expected = 'json string';

        $this->assertEquals($expected, $actual);
    }
}
