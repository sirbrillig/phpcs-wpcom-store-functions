<?php
declare(strict_types=1);

namespace WpcomStoreFunctions\Tests\Sniffs\StoreFunction;

use PHPUnit\Framework\TestCase;
use WpcomStoreFunctions\Tests\SniffTestHelper;
use function dirname;

class RiskyStoreFunctionSniffTest extends TestCase {
	public function testSniffRegistersWarningWhenUsingGetSubscription() {
		$fixtureFile = __DIR__ . '/fixtures/FileUsingGetSubscriptionFixture.php';
		$sniffFile = __DIR__ . '/../../../WpcomStoreFunctions/Sniffs/StoreFunction/RiskyStoreFunctionSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'WpcomStoreFunctions.StoreFunction.RiskyStoreFunctionSniff.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [
			4,
			5,
		];
		$this->assertEquals($expectedLines, $lines);
	}
}
