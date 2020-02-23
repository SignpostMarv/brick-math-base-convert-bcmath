<?php

declare(strict_types=1);

namespace SignpostMarv\Brick\Math\Calculator;

use function bcadd;
use function bcdiv;
use function bcmod;
use function bcmul;
use function preg_match;
use SignpostMarv\Brick\Math\Calculator;
use function strlen;
use function strpos;
use function substr;
use function trim;

/**
 * Calculator implementation built around the bcmath library.
 *
 * @internal
 */
class BcMathCalculator extends Calculator
{
	/**
	 * {@inheritdoc}
	 */
	public function add(string $a, string $b) : string
	{
		return bcadd($a, $b, 0);
	}

	/**
	 * {@inheritdoc}
	 */
	public function mul(string $a, string $b) : string
	{
		return bcmul($a, $b, 0);
	}

	/**
	 * {@inheritdoc}
	 */
	public function divQR(string $a, string $b) : array
	{
		$maybe = $this->MaybeEarlyExitDivQR($a, $b);

		if ( ! is_null($maybe)) {
			return $maybe;
		}

		$scale = 0;

		if (1 === preg_match('/\.(\d+)$/', $a, $matches)) {
			$scale = strlen($matches[1]);
		}

		if (1 === preg_match('/\.(\d+)$/', $b, $matches)) {
			$scale = (int) max($scale, strlen($matches[1]));
		}

		$q = (string) bcdiv($a, $b, 0);
		$r = trim((string) bcmod($a, $b, $scale + 1), '0');

		if (0 === strpos($r, '-0.')) {
			$r = '-' . substr($r, 2);
		}

		return [$q, $r];
	}
}
