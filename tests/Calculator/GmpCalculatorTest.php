<?php

declare(strict_types=1);

namespace SignpostMarv\Brick\Math\Tests\Calculator;

use Generator;
use InvalidArgumentException;
use SignpostMarv\Brick\Math\Calculator;
use SignpostMarv\Brick\Math\Calculator\GmpCalculator;
use SignpostMarv\Brick\Math\Tests\AbstractTestCase;

/**
 * Unit tests for class GmpCalculator.
 */
class GmpCalculatorTest extends AbstractCalculatorTest
{
    protected function ObtainCalculator() : Calculator
    {
        return new GmpCalculator();
    }
}
