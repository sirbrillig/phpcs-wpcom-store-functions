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
	 * @var string[]
	 */
	private array $functionNamesToWarn = [
		'get_subscription',
	];

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
		$phpcsFile->addWarning('Calling a WPCOM Store function is risky.', $stackPtr, 'Found');
	}
}
