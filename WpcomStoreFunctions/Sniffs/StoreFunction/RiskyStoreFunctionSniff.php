<?php

declare(strict_types=1);

namespace WpcomStoreFunctions\Sniffs\StoreFunction;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use WpcomStoreFunctions\Lib\Helpers;

class RiskyStoreFunctionSniff implements Sniff
{
	/**
	 * An array of function names to disallow with a warning.
	 *
	 * @var array<string>
	 */
	private array $functionNamesToWarn = [
		'get_subscription',
	];

	private string $defaultWarning = 'Calling a this function is resource heavy. It should not be used anywhere where performance is important.';

	public function register()
	{
		return [T_STRING];
	}

	public function process(File $phpcsFile, $stackPtr): void
	{
		$tokens = $phpcsFile->getTokens();
		$tokenContent = $tokens[$stackPtr]['content'] ?? '';
		if (! in_array($tokenContent, $this->functionNamesToWarn, true)) {
			return;
		}
		if (! Helpers::isFunctionCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isMethodCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isStaticCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isNamespacedCall($phpcsFile, $stackPtr)) {
			return;
		}
		$phpcsFile->addWarning($this->defaultWarning, $stackPtr, 'Found');
	}
}
