<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zero\StringConverter;

final class StringConverterTest extends TestCase
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
    public function testGivenTraditionalChineseText_WhenConvertZh2Hans_ThenReturnSimplifiedChinese()
    {
        // Arrange
        $text =  '我是誰';
        $expected = '我是谁';

        // Act
        $actual = StringConverter::convertZh2Hans($text);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testGivenSimplifiedChineseText_WhenConvertZh2Hant_ThenReturnTraditionalChinese()
    {
        // Arrange
        $text =  '我是谁';
        $expected = '我是誰';

        // Act
        $actual = StringConverter::convertZh2Hant($text);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testGivenTraditionalChineseText_WhenConvertZh2Hans_ThenReturnTraditionalChineseText()
    {
        // Arrange
        $text =  '我是誰';
        $expected = '我是誰';
        $mock = $this->getMockForTrait(StringConverter::class);

        // Act
        $actual = StringConverter::convertZh2Hant($text);

        // Assert
        $this->assertEquals($expected, $actual);
    }
}