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
    public function testGivenInputText_WhenConvertZh2Hans_ThenShowSimplifiedChinese()
    {
        // Arrange
        $inputText =  '我是誰';
        $expected = '我是谁';

        // Act
        $outputText = StringConverter::convertZh2Hans($inputText);

        // Assert
        $this->assertEquals($expected, $outputText);
    }

    /**
     * @test
     */
    public function testGivenInputText_WhenConvertZh2Hant_ThenTraditionalChinese()
    {
        // Arrange
        $inputText =  '我是谁';
        $expected = '我是誰';

        // Act
        $outputText = StringConverter::convertZh2Hant($inputText);

        // Assert
        $this->assertEquals($expected, $outputText);
    }
}