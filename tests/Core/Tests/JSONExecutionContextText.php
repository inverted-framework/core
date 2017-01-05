<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\JSONExecutionContext;
use \Inverted\Core\MissingConfigurationFileException;
use \Inverted\Core\InvalidConfigurationFileException;

/**
 *
 */
class JSONExecutionContextTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testJSONConfiguration() {
		// echo dirname(__FILE__).'/../../resources/Projects/simple.json';
		new JSONExecutionContext(dirname(__FILE__).'/../../resources/Projects/simple.json');
	}

	/**
	 * @test
	 */
	public function testMissingJSONConfiguration() {
		$this->expectException(MissingConfigurationFileException::class);

		new JSONExecutionContext('/path/to/any/config.json');
	}

	/**
	 * @test
	 */
	public function testJSONConfigurationWithBrokenComments() {
		$this->expectException(InvalidConfigurationFileException::class);

		new JSONExecutionContext(dirname(__FILE__).'/../../resources/Projects/broken1.json');
	}

	/**
	 * @test
	 */
	public function testJSONConfigurationWithBrokenJSON() {
		$this->expectException(InvalidConfigurationFileException::class);

		new JSONExecutionContext(dirname(__FILE__).'/../../resources/Projects/broken2.json');
	}
}
