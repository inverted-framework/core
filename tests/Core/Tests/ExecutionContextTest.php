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
	 * @var string
	 */
	private $_ns;

	/**
	 * @before
	 */
	public function setUp() {
		$this->_ns = '\\Inverted\\Core\\Tests\\Projects\\Simple';
		$classes   = ['First', 'Second', 'Fourth'];
		$hases     = ['MultipleParameters', 'Parameters', 'StaticConstructor'];

		$config  = new Configuration();
		$config->setNamespace($this->_ns);
		$config->setUseAutoload(true);

		foreach ($classes as $class) {
			$config->addRegistration(new Registration($class.'Class', $this->_ns));
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

	/**
	 * @test
	 */
	public function textClassFromExternalPackage() {
		$class_name = '\\Inverted\\Core\\Tests\\Projects\\Problems\\HasNoStronglyTypedParameter';
		$this->_config->addRegistration(new Registration($class_name, $this->_ns, [Registration::PARAMETERS => [3]]));

		$ctx = new ExecutionContext($this->_config);

		$classes = $ctx->getObjectsByClassName($class_name);

		$this->assertCount(1, $classes);
		$this->assertInstanceOf($class_name, $classes[0]);
	}
}
