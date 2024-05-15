<?php

namespace Cerdic\CssTidy\Test;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase {
	private static function getBaseDir() {
		return __DIR__ . '/Fixtures';
	}

	protected static function getCssFixtures() {
		$fixtures = [];

		$expectedFiles = \glob(self::getBaseDir() . '/**/*.expected.css');
		foreach ($expectedFiles as $expectedFile) {
			$cssCode = \str_replace('expected.', '', $expectedFile);
			$testName = \str_replace('.css', '', $cssCode);
			$testName = \str_replace(self::getBaseDir().'/', '', $testName);
			$phpFile = \str_replace('.css', '.php', $cssCode);
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$testName] = [
				'expectedReturnValue' => $settings['expectedReturnValue'] ?? true,
				'expected' => \file_get_contents($expectedFile),
				'setting' => $settings['settings'],
				'cssCode' => $cssCode,
			];
		}

		return $fixtures;
	}

	protected static function getPhpFixtures() {
		$fixtures = [];

		$expectedFiles = \array_merge(
			\glob(self::getBaseDir() . '/*/*.expected.php'),
			\glob(self::getBaseDir() . '/*/*/*.expected.php')
		);
		foreach ($expectedFiles as $expectedFile) {
			$cssCode = \str_replace('.expected.php', '.css', $expectedFile);
			$phpFile = \str_replace('.expected', '', $expectedFile);
			$testName = \str_replace('.php', '', $phpFile);
			$testName = \str_replace(self::getBaseDir().'/', '', $testName);
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$testName] = [
				'expectedReturnValue' => $settings['expectedReturnValue'] ?? true,
				'expected' => [41 => require $expectedFile],
				'setting' => $settings['settings'],
				'cssCode' => $cssCode,
			];
		}

		return $fixtures;
	}

	protected static function getPhpFullFixtures() {
		$fixtures = [];

		$expectedFiles = \array_merge(
			\glob(self::getBaseDir() . '/*/*.full-expected.php'),
			\glob(self::getBaseDir() . '/*/*/*.full-expected.php')
		);
		foreach ($expectedFiles as $expectedFile) {
			$cssCode = \str_replace('.full-expected.php', '.css', $expectedFile);
			$phpFile = \str_replace('.full-expected', '', $expectedFile);
			$testName = \str_replace('.php', '', $phpFile);
			$testName = \str_replace(self::getBaseDir().'/', '', $testName);
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$testName] = [
				'expectedReturnValue' => $settings['expectedReturnValue'] ?? true,
				'expected' => require $expectedFile,
				'setting' => $settings['settings'],
				'cssCode' => $cssCode,
			];
		}

		return $fixtures;
	}
}
