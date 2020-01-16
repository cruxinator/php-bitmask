<?php

namespace Cruxinator\BitMask\Tests;

class BitmaskTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getValue().
     */
    public function testGetValue()
    {
        $value = new EnumFixture(EnumFixture::FOUR);
        $this->assertEquals(EnumFixture::FOUR, $value->getValue());
        $value = new EnumFixture(EnumFixture::EIGHT);
        $this->assertEquals(EnumFixture::EIGHT, $value->getValue());
        $value = new EnumFixture(EnumFixture::THIRTYTWO);
        $this->assertEquals(EnumFixture::THIRTYTWO, $value->getValue());
    }
    
    public function testFoo()
    {
        $Val =new EnumFixture(EnumFixture::ONE | EnumFixture::TWO);
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
        $this->expectExceptionMessage('Method startTheDance not found on Class Cruxinator\Bitmask\Tests\EnumFixture');
        $foo = new EnumFixture(EnumFixture::FOUR);

        $foo->startTheDance();
    }

    public function testGetName()
    {
        $foo = new EnumFixture(EnumFixture::ONE | EnumFixture::TWO);

        $expected = 'BitMask';
        $actual = $foo->getName();
        $this->assertEquals($expected, $actual);
    }

    public function testToString()
    {
        $foo = new EnumFixture(EnumFixture::ONE | EnumFixture::TWO);

        $expected = 'BitMask['.PHP_EOL.'\'ONE\' => TRUE'.PHP_EOL.'\'TWO\' => TRUE'.PHP_EOL.'\'FOUR\' => FALSE'.PHP_EOL;
        $expected .= '\'EIGHT\' => FALSE'.PHP_EOL.'\'SIXTEEN\' => FALSE'.PHP_EOL.'\'THIRTYTWO\' => FALSE'.PHP_EOL.']';
        $expected .= PHP_EOL;
        $actual = $foo->__toString();
        $this->assertEquals($expected, $actual);
    }

    public function testGetKey()
    {
        $foo = new EnumFixture(EnumFixture::ONE | EnumFixture::THIRTYTWO);
        $expected = ['ONE', 'THIRTYTWO'];

        $actual = $foo->getKey();
        $this->assertEquals($expected, $actual);
    }
}
