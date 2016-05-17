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
	 * 
	 */
	private $_registry;

	/**
	 * 
	 */
	private $_ssalc;

	public function setUp() {
		$reg = new Registration('SecondClass', '\\Inverted\\Core\\Tests\\Projects\\Simple', [Registration::IDENTIFIER => 'second']);
		
		$this->_ssalc    = new RegisteredClass($reg);
		$this->_registry = new ClassRegistry();

		$this->_registry->addClassToRegistry($this->_ssalc);
	}

	/**
	 * @test
	 */
	public function testIndexAndRetrieve() {
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByClassName('Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByInterface('Inverted\\Core\\Tests\\Projects\\Simple\\MyInterface'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesBySuperClass('Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByIdentifier('second'));
	}

	/**
	 * @test
	 */
	public function testAccessingWithLeadingSlash() {
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByClassName('\\Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByInterface('\\Inverted\\Core\\Tests\\Projects\\Simple\\MyInterface'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesBySuperClass('\\Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass'));
		$this->assertEquals([$this->_ssalc], $this->_registry->getClassesByIdentifier('second'));
	}
}

