<?php
declare(strict_types=1);

namespace WpcomStoreFunctions\Lib;

class Helpers {
	public static function isFunctionCall($phpcsFile, $stackPtr): bool {
		$prevFunctionPtr = $phpcsFile->findPrevious([T_FUNCTION], $stackPtr - 1, $stackPtr - 3);
		if ($prevFunctionPtr) {
			return false;
		}
		$tokens = $phpcsFile->getTokens();
		$nextParenPtr = $phpcsFile->findNext([T_OPEN_PARENTHESIS], $stackPtr + 1, $stackPtr + 2);
		return ($nextParenPtr && isset($tokens[$nextParenPtr]));
	}

	public static function isMethodCall($phpcsFile, $stackPtr): bool {
		$prevSymbolPtr = $phpcsFile->findPrevious([T_OBJECT_OPERATOR], $stackPtr - 1, $stackPtr - 2);
		return (bool)$prevSymbolPtr;
	}

	public static function isFunctionAMethod($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		return ! empty($currentToken['conditions']);
	}

	public static function isStaticCall($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_DOUBLE_COLON], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public static function isNamespacedCall($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_NS_SEPARATOR], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public static function isFunctionDefined($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$functionName = $tokens[$stackPtr]['content'] ?? '';
		$functionPtr = $phpcsFile->findNext([T_FUNCTION], 0);
		while ($functionPtr) {
			$thisFunctionName = $phpcsFile->getDeclarationName($functionPtr);
			if ($functionName === $thisFunctionName && ! self::isFunctionAMethod($phpcsFile, $functionPtr)) {
				return true;
			}
			$functionPtr = $phpcsFile->findNext([T_FUNCTION], $functionPtr + 1);
		}
		return false;
	}
}
