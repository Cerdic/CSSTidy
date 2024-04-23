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
			$phpFile = \str_replace('.css', '.php', $cssCode);
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$settings['test']] = [
				'expectedReturnValue' => $settings['expectedReturnValue'],
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
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$settings['test']] = [
				'expectedReturnValue' => $settings['expectedReturnValue'],
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
			$cssCode = \file_exists($cssCode) ? \file_get_contents($cssCode) : '';

			$settings = ['test' => 'empty', 'expectedReturnValue' => false, 'settings' => []];
			if (\file_exists($phpFile)) {
				/** @var array{test:non-empty-string,expectedReturnValue:bool,settings:array<string,mixed>} $settings */
				$settings = require $phpFile;
			}

			$fixtures[$settings['test']] = [
				'expectedReturnValue' => $settings['expectedReturnValue'],
				'expected' => require $expectedFile,
				'setting' => $settings['settings'],
				'cssCode' => $cssCode,
			];
		}

		return $fixtures;
	}
}
