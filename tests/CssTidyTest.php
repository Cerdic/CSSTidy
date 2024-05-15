<?php

namespace Cerdic\CssTidy\Test;

use csstidy;

/**
 * @covers csstidy
 */
class CssTidyTest extends TestCase {
	private $csstidy;

	protected function setUp(): void {
		$this->csstidy = new csstidy;
	}

	public static function dataGetSetCfg() {
		return [
			'test' => [
				'expected' => false,
				'expected_success' => false,
				'setting' => 'test',
				'value' => 'test',
			],
			'case_properties' => [
				'expected' => 1,
				'expected_success' => true,
				'setting' => 'case_properties',
				'value' => 2,
			],
		];
	}

	/**
	 * @dataProvider dataGetSetCfg
	 *
	 * @covers csstidy_optimise
	 */
	public function testGetSetCfg($expected, $expected_success, $setting, $value) {
		// Given
		$previous = $this->csstidy->get_cfg($setting);

		// When
		$actual = $this->csstidy->set_cfg($setting, $value);
		$expected_value = $actual ? $value : false;

		//Then
		$this->assertEquals($expected, $previous);
		$this->assertEquals($expected_success, $actual, 'wrong setting');
		$this->assertEquals($expected_value, $this->csstidy->get_cfg($setting));
	}

	/**
	 * @covers csstidy_optimise
	 * @covers csstidy_print
	 */
	public function testGetSetCfgByArray() {
		// Given
		// When
		$this->csstidy->set_cfg([
			'test' => 'test',
			'case_properties' => 2,
			'template' => 'highest',
		]);

		// Then
		$this->assertEquals('test', $this->csstidy->get_cfg('test'));
		$this->assertEquals(2, $this->csstidy->get_cfg('case_properties'));
	}

	public static function dataParse() {
		return array_merge([
			'empty' => [
				'expectedReturnValue' => false,
				'expected' => '',
				'setting' => [],
				'cssCode' => '',
			],
		], self::getCssFixtures(), self::getPhpFixtures(), self::getPhpFullFixtures());
	}

	/**
	 * @dataProvider dataParse
	 *
	 * @covers csstidy_optimise
	 * @covers csstidy_print
	 */
	public function testParse($expectedReturnValue, $expected, $setting, $cssCode) {
		// Given
		// When
		$this->csstidy->set_cfg($setting);
		$actualReturnValue = $this->csstidy->parse($cssCode);

		$actual = \is_array($expected) ? $this->csstidy->css : $this->csstidy->print->plain($setting['default_media'] ?? '');

		// Then
		$this->assertEquals($expectedReturnValue, $actualReturnValue);
		$this->assertEquals($expected, $actual);
	}
}
