<?php

declare(strict_types=1);

namespace Cruxinator\BitMask\Tests;

class BitmaskTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getValue().
     */
    public function testGetValue()
    {
        $value = new BitMaskFixture(BitMaskFixture::FOUR);
        $this->assertEquals(BitMaskFixture::FOUR, $value->getValue());
        $value = new BitMaskFixture(BitMaskFixture::EIGHT);
        $this->assertEquals(BitMaskFixture::EIGHT, $value->getValue());
        $value = new BitMaskFixture(BitMaskFixture::THIRTYTWO);
        $this->assertEquals(BitMaskFixture::THIRTYTWO, $value->getValue());
    }

    public function testFoo()
    {
        $Val =new BitMaskFixture(BitMaskFixture::ONE | BitMaskFixture::TWO);
        $this->assertTrue($Val->isONE());
        $this->assertTrue($Val->isTWO());
        $this->assertFalse($Val->isFOUR());
        $Val->setFOUR(true);
        $this->assertTrue($Val->isFOUR());
        $Val->setTWO(false);
        $this->assertFalse($Val->isTWO());
    }

    public function testBadCall()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Enum startTheDance not found on Cruxinator\BitMask\Tests\BitMaskFixture'
        );
        $foo = new BitMaskFixture(BitMaskFixture::FOUR);

        $foo->startTheDance();
    }

    public function testGetName()
    {
        $foo = new BitMaskFixture(BitMaskFixture::ONE | BitMaskFixture::TWO);

        $expected = 'BitMask';
        $actual   = $foo->getName();
        $this->assertEquals($expected, $actual);
    }

    public function testToString()
    {
        $foo = new BitMaskFixture(BitMaskFixture::ONE | BitMaskFixture::TWO);

        $expected = 'BitMask[' . PHP_EOL . '\'ONE\' => TRUE' . PHP_EOL . '\'TWO\' => TRUE' . PHP_EOL . '\'FOUR\' => FALSE' . PHP_EOL;
        $expected .= '\'EIGHT\' => FALSE' . PHP_EOL . '\'SIXTEEN\' => FALSE' . PHP_EOL . '\'THIRTYTWO\' => FALSE' . PHP_EOL . ']';
        $expected .= PHP_EOL;
        $actual = $foo->__toString();
        $this->assertEquals($expected, $actual);
    }

    public function testGetKey()
    {
        $foo      = new BitMaskFixture(BitMaskFixture::ONE | BitMaskFixture::THIRTYTWO);
        $expected = ['ONE', 'THIRTYTWO'];

        $actual = $foo->getKey();
        $this->assertEquals($expected, $actual);
    }

    public function testZero(){
        $foo = BitMaskZeroFixture::ONE();
        $this->assertTrue($foo->isONE());
        $this->assertFalse($foo->isZERO());
        $foo->setONE(false);
        $this->assertFalse($foo->isONE());
        $this->assertTrue($foo->isZERO());
        $foo->setTHIRTYTWO(true);
        $this->assertTrue($foo->isTHIRTYTWO());
        $this->assertFalse($foo->isZERO());
        $foo->setZERO();
        $this->assertTrue($foo->isZERO());
        $this->assertFalse($foo->isTHIRTYTWO());
    }
}
