<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Formatter;


class FormatterTest extends TestCase{

    private $formatter;
    public function setUp()
    {
        $this->formatter = new Formatter();
    }

    public function tearDown()
    {
        unset($this->formatter);
    }
    /**
     * @dataProvider provideCurrencyAmount
     * @param $input
     * @param $expected
     * @param $msg
     */
    public function testCurrencyAmount($input, $expected, $msg)
    {
        $this->assertSame(
            $expected,
            $this->formatter->currencyAmount($input),
            $msg
        );
    }

    public function provideCurrencyAmount()
    {
        return [
            [1, 1.00, '1 should be transformed into 1.00'],
            [1.1, 1.10, '1 should be transformed into 1.10'],
            [1.11, 1.11, '1 should be transformed into 1.11'],
            [1.111, 1.11, '1 should be transformed into 1.11'],
        ];
    }
}