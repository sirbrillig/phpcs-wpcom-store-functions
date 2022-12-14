<?php

namespace WpcomStoreFunctions\Tests;

use PHPUnit\Framework\TestCase;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class BaseTestCase extends TestCase
{
	const STANDARD_NAME = 'WpcomStoreFunctions';

	const DISALLOWED_SNIFF_CODE = 'WpcomStoreFunctions.StoreFunction.RiskyStoreFunctionSniff.Found';

	public function prepareLocalFileForSniffs($fixtureFile)
	{
		$sniffFile = __DIR__ . '/../WpcomStoreFunctions/Sniffs/StoreFunction/RiskyStoreFunctionSniff.php';

		$config            = new Config();
		$config->cache     = false;
		$config->standards = [self::STANDARD_NAME];
		$config->ignored   = [];

		$sniffFiles = [realpath($sniffFile)];
		$ruleset    = new Ruleset($config);
		$ruleset->registerSniffs($sniffFiles, [], []);
		$ruleset->populateTokenListeners();
		if (! file_exists($fixtureFile)) {
			throw new \Exception('Fixture file does not exist: ' . $fixtureFile);
		}
		return new LocalFile($fixtureFile, $ruleset, $config);
	}

	public function getLineNumbersFromMessages(array $messages)
	{
		$lines = array_keys($messages);
		sort($lines);
		return $lines;
	}

	public function getWarningLineNumbersFromFile(LocalFile $phpcsFile)
	{
		return $this->getLineNumbersFromMessages($phpcsFile->getWarnings());
	}

	public function getErrorLineNumbersFromFile(LocalFile $phpcsFile)
	{
		return $this->getLineNumbersFromMessages($phpcsFile->getErrors());
	}

	public function getFixture($fixtureFilename)
	{
		$fullPath = __DIR__ . '/Sniffs/StoreFunction/fixtures/' . $fixtureFilename;
		$path = realpath( $fullPath );
		if (! $path) {
			throw new \Exception('Fixture file could not be found: ' . $fullPath);
		}
		return $path;
	}
}
