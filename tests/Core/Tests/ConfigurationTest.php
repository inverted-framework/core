<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\Configuration;
use \Inverted\Core\Registration;

/**
 * 
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testBeanlikeProperties() {
		$config = new Configuration();
		$regs   = [];

		$ns   = '\\Any\\Namespace\\Will\\Do';
		$bool = false;

		$regs[] = new Registration('X', $ns);
		$regs[] = new Registration('Y', $ns);
		$reg    = new Registration('Z', '\\Another\\Namespace');

		$this->assertEquals('', $config->getNamespace());
		$this->assertEquals(true, $config->getUseAutoload());
		$this->assertEquals([], $config->getRegistrations());

		$config->setNamespace($ns);
		$config->setUseAutoload($bool);
		$config->setRegistrations($regs);

		$this->assertEquals($ns, $config->getNamespace());
		$this->assertEquals(false, $config->getUseAutoload());
		$this->assertEquals($regs, $config->getRegistrations());

		$config->addRegistration($reg);
		$regs[] = $reg;

		$this->assertEquals($regs, $config->getRegistrations());
	}
}

