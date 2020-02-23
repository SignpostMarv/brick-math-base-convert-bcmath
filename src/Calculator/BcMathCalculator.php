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
	const SCALE_INT = 0;

	const DEFAULT_SCALE = 0;

	const PREG_MATCH = 1;

	const NUDGE_SCALE = 1;

	const FIRST_MATCH = 1;

	/**
	 * {@inheritdoc}
	 */
	public function add(string $a, string $b) : string
	{
		return bcadd($a, $b, self::SCALE_INT);
	}

	/**
	 * {@inheritdoc}
	 */
	public function mul(string $a, string $b) : string
	{
		return bcmul($a, $b, self::SCALE_INT);
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

		$scale = self::DEFAULT_SCALE;

		if (self::PREG_MATCH === preg_match('/\.(\d+)$/', $a, $matches)) {
			$scale = strlen($matches[self::FIRST_MATCH]);
		}

		if (self::PREG_MATCH === preg_match('/\.(\d+)$/', $b, $matches)) {
			$scale = (int) max($scale, strlen($matches[self::FIRST_MATCH]));
		}

		/** @var string */
		$q = bcdiv($a, $b, self::SCALE_INT);
		/** @var string */
		$r = bcmod($a, $b, $scale + self::NUDGE_SCALE);
		$r = trim($r, '0');

		if (0 === strpos($r, '-0.')) {
			$r = '-' . substr($r, 2);
		}

		return [$q, $r];
	}
}
