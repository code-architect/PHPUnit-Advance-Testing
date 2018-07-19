<?php

namespace TDD;


class Formatter{

    public function currencyAmount($input)
    {
        return round($input, 2);
    }
}