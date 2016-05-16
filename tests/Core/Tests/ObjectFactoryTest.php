<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\ObjectFactory;
use \Inverted\Core\RegisteredClass;
use \Inverted\Core\Registration;

/**
 * 
 */
class ObjectFactoryTest extends \PHPUnit_Framework_TestCase {
	const FIRST_CLASS  = '\\Inverted\\Core\\Tests\\Projects\\Simple\\FirstClass';
	const SECOND_CLASS = '\\Inverted\\Core\\Tests\\Projects\\Simple\\SecondClass';
	const MY_INTERFACE = '\\Inverted\\Core\\Tests\\Projects\\Simple\\MyInterface';

	/**
	 * @var ObjectFactory
	 */
	private $_factory;

	/**
	 *
	 */
	public function setUp() {
		$reg   = new Registration('SecondClass', '\\Inverted\\Core\\Tests\\Projects\\Simple', [Registration::IDENTIFIER => 'second']);
		$ssalc = new RegisteredClass($reg);

		$this->_factory = new ObjectFactory();

		$this->_factory->addClassToRegistry($ssalc);
	}

	/**
	 * @test
	 */
	public function testGetObjectsByClassName() {
		$obj = $this->_factory->getObjectsByClassName(ltrim(self::SECOND_CLASS, '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SECOND_CLASS, $obj[0]);		
	}

	/**
	 * @test
	 */
	public function testGetObjectsByInterface() {
		$obj = $this->_factory->getObjectsByInterface(ltrim(self::MY_INTERFACE, '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SECOND_CLASS, $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsBySuperClass() {
		$obj = $this->_factory->getObjectsBySuperClass(ltrim(self::FIRST_CLASS, '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SECOND_CLASS, $obj[0]);
	}

	/**
	 * @test
	 */
	public function testSingleton() {
		$obj1 = $this->_factory->getObjectsBySuperClass(ltrim(self::FIRST_CLASS, '\\'));
		$obj2 = $this->_factory->getObjectsByInterface(ltrim(self::MY_INTERFACE, '\\'));

		$obj1[0]->x = 1;

		$this->assertEquals(1, $obj2[0]->x, 'The whole purpose of the system is broken.');
	}
}

