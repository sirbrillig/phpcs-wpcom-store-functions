<?php

declare(strict_types=1);

namespace WpcomStoreFunctions\Tests\Sniffs\StoreFunction;

use WpcomStoreFunctions\Tests\BaseTestCase;

class RiskyStoreFunctionSniffTest extends BaseTestCase
{
	public function testSniffRegistersWarningWhenUsingGetSubscription()
	{
		$fixtureFile = $this->getFixture('FileUsingGetSubscriptionFixture.php');
		$phpcsFile = $this->prepareLocalFileForSniffs($fixtureFile);
		$phpcsFile->process();

		$lines = $this->getWarningLineNumbersFromFile($phpcsFile);
		$expectedWarnings = [
			4,
		];
		$this->assertSame($expectedWarnings, $lines);
	}
}
