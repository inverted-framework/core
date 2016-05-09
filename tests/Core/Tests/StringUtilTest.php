<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\StringUtil;

/**
 * 
 */
class StringUtilTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testStartsWith() {
		$this->assertTrue(StringUtil::startsWith('Hello world!', 'H'));
		$this->assertFalse(StringUtil::startsWith('Hello world!', 'e`'));
	}

	/**
	 * @test
	 */
	public function testEndsWith() {
		$this->assertTrue(StringUtil::endsWith('Hello world!', '!'));
		$this->assertFalse(StringUtil::endsWith('Hello world!', 'd'));
	}
}