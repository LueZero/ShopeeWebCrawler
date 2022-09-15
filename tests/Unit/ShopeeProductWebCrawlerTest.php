<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BigGo\InterviewQuestion\ShopeeProductWebCrawler;

final class ShopeeProductWebCrawlerTest extends TestCase
{
    /**
     * @var SerializerInterface&MockObject
     */
    protected $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = $this->createMock(ShopeeProductWebCrawler::class);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function testGivenCategoryTree_WhenShow_ThenFindArrayKey()
    {
        // Arrange
        $this->sut->expects($this->any())->method('getCategoryTree')->will($this->returnSelf());
        $this->sut->expects($this->any())->method('toArray')->will($this->returnValue([
            'data'=> ['category_list' => [
                [
                    "catid" => 11040767,
                    "parent_catid" => 11040766,
                    "name" => "Top",
                    "display_name" => "上衣類",
                    "image" => "d2f4a2d40a188db93ac1d765a2d3b143",
                    "unselected_image" => null,
                    "selected_image" => null,
                    "level" => 2,
                    "children" => null,
                    "block_buyer_platform" => null
                ],
            ]]
        ]));

        // Act
        $response = $this->sut->getCategoryTree()->toArray();

        // Assert
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('category_list', $response['data']);
    }

    /**
     * @test
     */
    public function testGivenSearchItemsAndUseExistingCategoryID_WhenShow_ThenFindArrayKey()
    {
        // Arrange
        $categoryId = 11041645;

        $this->sut->expects($this->any())->method('getSearchItems')->with(11041645, 1, 0)->will($this->returnSelf());
        $this->sut->expects($this->any())->method('toArray')->will($this->returnValue([
            'items' => [
                "item_basic" => [
                    "itemid" => 6840261731,
                    "name" => "PTCG 中文版 寶可夢卡牌【普卡一張一元】 100張以內無重複出貨~",
                    "price" =>  100000,
                    "price_min" => 100000,
                    "price_max" => 100000,
                ],
            ]
        ]));

        // Act
        $response = $this->sut->getSearchItems($categoryId, 1, 0)->toArray();

        // Assert
        $this->assertArrayHasKey('items', $response);
        $this->assertArrayHasKey('item_basic', $response['items']);
    }

    /**
     * @test
     */
    public function testGivenSearchItemsAndUseNotExistedCategoryID_WhenShow_ThenItemsArrayCount()
    {
        // Arrange
        $categoryId = 123;
        $this->sut->expects($this->any())->method('getSearchItems')->with($this->isType('integer'), $this->isType('integer'), $this->isType('integer'))->will($this->returnSelf());
        $this->sut->expects($this->any())->method('toArray')->will($this->returnValue([
            'items' => []
        ]));

        // Act
        $response = $this->sut->getSearchItems($categoryId, 1, 0)->toArray();
        
        // Assert
        $this->assertEquals(0, count($response['items']));
    }

    /**
     * @test
     */
    public function testGivenSearchItemsAndUseExistingCategoryID_WhenShow_ThenErrorException()
    {
        // Arrange
        $categoryId = 123;
        $this->sut->expects($this->any())->method('getSearchItems')->with($this->isType('integer'), $this->isType('integer'), $this->isType('integer'))->will($this->returnSelf());
        $this->sut->expects($this->any())->method('toArray')->will($this->throwException(new ErrorException('HTTP ERROR')));

        // Act & Assert
        $this->expectException(ErrorException::class);
        $this->sut->getSearchItems($categoryId, 1, 0)->toArray();
    }
}
