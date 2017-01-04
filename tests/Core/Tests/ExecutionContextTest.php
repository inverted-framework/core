<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\Configuration;
use \Inverted\Core\ExecutionContext;
use \Inverted\Core\Registration;

/**
 * 
 */
class ExecutionContextTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Configuration
	 */
	private $_config;

	/**
	 * @before
	 */
	public function setUp() {
		$classes = ['First', 'Second', 'Fourth'];
		$hases   = ['MultipleParameters', 'Parameters', 'StaticConstructor'];
		$ns      = '\\Inverted\\Core\\Tests\\Projects\\Simple';

		$config  = new Configuration();
		$config->setNamespace($ns);
		$config->setUseAutoload(true);

		foreach ($classes as $class) {
			$config->addRegistration(new Registration($class.'Class', $ns));
		}

		foreach ($hases as $class) {
		//	$config->addRegistration(new Registration('Has'.$class, $ns));
		}

		$this->_config = $config;
	}

	/**
	 * @test
	 */
	public function testInitialization() {
		$ctx = new ExecutionContext($this->_config);

		$classes = $ctx->getObjectsByClassName('FourthClass');
		$this->assertEmpty($classes);

		$classes = $ctx->getObjectsByClassName('\\Inverted\\Core\\Tests\\Projects\\Simple\\FourthClass');
		$this->assertEquals(1, count($classes));

		$classes = $ctx->getObjectsByClassName('\\Inverted\\Core\\Tests\\Projects\\Simple\\ThirdClass');
		$this->assertEquals(1, count($classes));
	}
}


