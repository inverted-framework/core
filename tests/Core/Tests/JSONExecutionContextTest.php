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
		$class_name = "\\Inverted\\Core\\Tests\\Projects\\Problems\\HasNoStronglyTypedParameter";

		$ctx = new JSONExecutionContext(dirname(__FILE__).'/../../resources/Projects/simple.json');
		$obj = $ctx->getObjectsByClassName($class_name);

		$this->assertCount(1, $obj);
		$this->assertInstanceOf($class_name, $obj[0]);
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

	/**
	 * @test
	 */
	public function testJSONConfigurationWithInclude() {
		$class_names = [
			  '\\Inverted\\Core\\Tests\\Projects\\Problems\\HasNoStronglyTypedParameter'
			, '\\Inverted\\Core\\Tests\\Projects\\Problems\\FirstImplementation'
			, '\\Inverted\\Core\\Tests\\Projects\\Problems\\RequiresRootInterface'
			, '\\Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass'
		];

		$ctx = new JSONExecutionContext(dirname(__FILE__).'/../../resources/Projects/includer.json');

		foreach ($class_names as $class_name) {
			$obj = $ctx->getObjectsByClassName($class_name);

			$this->assertCount(1, $obj);
			$this->assertInstanceOf($class_name, $obj[0]);
		}
	}
}
