<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase{

    private $receipt;
    private $formatter;
	public function setUp()
	{
	    $this->formatter = $this->getMockBuilder('TDD\Formatter')
                                ->setMethods(['currencyAmount'])
                                ->getMock();

	    $this->formatter->expects($this->any())
                                        ->method('currencyAmount')
                                        ->with($this->anything())
                                        ->will($this->returnArgument(0));
		$this->receipt = new Receipt($this->formatter);
	}

	public function tearDown()
	{
		unset($this->receipt);
	}

    /**
     * @dataProvider provideSubTotal
     */
	public function testSubTotal($items, $expected)
	{
		$coupon = null;
		$output = $this->receipt->subTotal($items, $coupon);

		$this->assertEquals(
			$expected,
			$output, 
			"When summing the total should equals 10 {$expected}"
		);
	}

    /**
     * The data provider for testSubTotal
     */
	public function provideSubTotal()
    {
        return [
            [[1, 2, 5, 8], 16],
            [[-1, 2, 5, 8], 14],
            [[1, 2, 8], 11],
        ];
    }

    public function testSubTotalAndCoupon()
    {
        $items = [0, 2, 5, 8];
        $coupon = 0.20;
        $output = $this->receipt->subTotal($items, $coupon);

        $this->assertEquals(
            12,
            $output,
            'When summing the total should equals 12'
        );
    }


    public function testSubTotalException()
    {
        $items = [0, 2, 5, 8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $this->receipt->subTotal($items, $coupon);
    }

    public function testPostTaxTotal()
    {
        $items = [1, 2, 5, 8];
        $tax = 0.20;
        $coupon = null;
        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'subTotal'])
            ->setConstructorArgs([$this->formatter])
            ->getMock();

        $receipt->expects($this->once())
            ->method('subTotal')
            ->with($items, $coupon)
            ->will($this->returnValue(10.00));

        $receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->will($this->returnValue(1.00));

        $result = $receipt->postTaxTotal([1,2,5,8], null);
        $this->assertEquals(11.00, $result);

    }

	public function testTax()
    {
        $inputAmount = 10.00;
        $this->receipt->tax = 0.10;
        $output = $this->receipt->tax($inputAmount);
        $this->assertEquals(
            1.00,
            $output,
            'The Tax calculation should equal 1.00'
        );
    }



}
