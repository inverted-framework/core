<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\ObjectFactory;
use \Inverted\Core\RegisteredClass;
use \Inverted\Core\Registration;

/**
 * 
 */
class ObjectFactoryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testGetObjectsByClassName() {
		$reg     = new Registration('SecondClass', '\\Inverted\\Core\\Tests\\Projects\\Simple', [Registration::IDENTIFIER => 'second']);
		$ssalc   = new RegisteredClass($reg);
		$factory = new ObjectFactory();

		$factory->addClassToRegistry($ssalc);

		$obj = $factory->getObjectsByClassName('Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass');

		$this->assertInstanceOf('\\Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass', $obj[0]);
	}
}

