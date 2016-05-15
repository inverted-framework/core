<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\ClassRegistry;
use \Inverted\Core\RegisteredClass;
use \Inverted\Core\Registration;

/**
 * 
 */
class ClassRegistryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testIndexAndRetrieve() {
		$reg      = new Registration('SecondClass', '\\Inverted\\Core\\Tests\\Projects\\Simple', [Registration::IDENTIFIER => 'second']);
		$ssalc    = new RegisteredClass($reg);
		$registry = new ClassRegistry();

		$registry->addClassToRegistry($ssalc);

		$this->assertEquals([$ssalc], $registry->getClassesByClassName('Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass'));
		$this->assertEquals([$ssalc], $registry->getClassesByInterface('Inverted\\Core\\Tests\\Projects\\Simple\\MyInterface'));
		$this->assertEquals([$ssalc], $registry->getClassesBySuperClass('Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass'));
		$this->assertEquals([$ssalc], $registry->getClassesByIdentifier('second'));
	}
}

