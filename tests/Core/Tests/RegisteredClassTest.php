<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\Registration;
use \Inverted\Core\RegisteredClass;

/**
 * 
 */
class RegisteredClassTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testCreation() {
		$reg   = new Registration('SecondClass', '\\Inverted\\Core\\Tests\\Projects\\Simple');
		$ssalc = new RegisteredClass($reg);

		$this->assertEquals('Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass', $ssalc->getClass());
		$this->assertEquals(['Inverted\\Core\\Tests\\Projects\\Simple\\MyInterface'], $ssalc->getInterfaces());
		$this->assertEquals(['Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass'], $ssalc->getSuperClasses());
	}

	/**
	 * @test
	 * @expectedException \ReflectionException
	 */
	public function testNonexistentClass() {
		// $this->expectException(\Inverted\Core\ClassNotFoundException::class);

		$reg   = new Registration('SecondClass');
		$ssalc = new RegisteredClass($reg);
	}
}

