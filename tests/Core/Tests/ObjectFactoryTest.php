<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\ObjectFactory;
use \Inverted\Core\RegisteredClass;
use \Inverted\Core\Registration;

/**
 * 
 */
class ObjectFactoryTest extends \PHPUnit_Framework_TestCase {
	const SIMPLE_PKG  = '\\Inverted\\Core\\Tests\\Projects\\Simple';
	const PROBLEM_PKG = '\\Inverted\\Core\\Tests\\Projects\\Problems';

	/**
	 * @var ObjectFactory
	 */
	private $_factory;

	/**
	 *
	 */
	public function setUp() {
		$this->_factory = new ObjectFactory();

	}

	/**
	 * @test
	 */
	public function testGetObjectsByClassName() {
		$this->_setupSecondClass();

		$obj = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\SecondClass', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);		
	}

	/**
	 * @test
	 */
	public function testGetObjectsByInterface() {
		$this->_setupSecondClass();

		$obj = $this->_factory->getObjectsByInterface(ltrim(self::SIMPLE_PKG.'\\MyInterface', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsBySuperClass() {
		$this->_setupSecondClass();

		$obj = $this->_factory->getObjectsBySuperClass(ltrim(self::SIMPLE_PKG.'\\FirstClass', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsByIdentifier() {
		$this->_setupSecondClass();

		$obj = $this->_factory->getObjectsByIdentifier('second');

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testSingleton() {
		$this->_setupSecondClass();

		$obj1 = $this->_factory->getObjectsBySuperClass(ltrim(self::SIMPLE_PKG.'\\FirstClass', '\\'));
		$obj2 = $this->_factory->getObjectsByInterface(ltrim(self::SIMPLE_PKG.'\\MyInterface', '\\'));

		$obj1[0]->x = 1;

		$this->assertEquals(1, $obj2[0]->x, 'The whole purpose of the system is broken.');
	}

	/**
	 * @test
	 */
	public function testNonSingleton() {
		$reg   = new Registration('ThirdClass', self::SIMPLE_PKG, [Registration::SINGLETON => false]);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);

		$obj1 = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\ThirdClass', '\\'));
		$obj2 = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\ThirdClass', '\\'));

		$obj1[0]->x = 1;

		$this->assertNotEquals(1, $obj2[0]->x, 'The whole purpose of the system is broken.');
	}

	/**
	 * @test
	 */
	public function testStaticConstructor() {
		$reg   = new Registration('HasStaticConstructor', self::SIMPLE_PKG, [Registration::CONSTRUCTOR => 'getInstance']);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);

		$obj = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\HasStaticConstructor');

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\HasStaticConstructor', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testInstantiationWithLeadingSlash() {
		$this->_setupSecondClass();

		$objs   = [];
		$objs[] = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\SecondClass');
		$objs[] = $this->_factory->getObjectsByInterface(self::SIMPLE_PKG.'\\MyInterface');
		$objs[] = $this->_factory->getObjectsBySuperClass(self::SIMPLE_PKG.'\\FirstClass');
		$objs[] = $this->_factory->getObjectsByIdentifier('second');

		foreach ($objs as $obj) {
			$this->assertCount(1, $obj);
			$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
		}
	}

	/**
	 * @test
	 */
	public function testClassInstantiationWithParameters() {
		$this->_setupSecondClass();

		$reg   = new Registration('HasParameters', self::SIMPLE_PKG);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);

		$objs = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\HasParameters');

		$this->assertCount(1, $objs);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\HasParameters', $objs[0]);

	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\CircularDependencyException
	 */
	public function testCircularDependency() {
		$classes = ['A', 'B', 'C'];
		foreach ($classes as $class) {
			$reg   = new Registration($class, self::PROBLEM_PKG);
			$ssalc = new RegisteredClass($reg);

			$this->_factory->addClassToRegistry($ssalc);
		}

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\A');
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\TooManyClassesException
	 */
	public function testTooManyClassesMatch() {
		$classes = ['FirstImplementation', 'SecondImplementation', 'RequiresRootInterface'];
		foreach ($classes as $class) {
			$reg   = new Registration($class, self::PROBLEM_PKG);
			$ssalc = new RegisteredClass($reg);

			$this->_factory->addClassToRegistry($ssalc);
		}

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\RequiresRootInterface');

	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\ClassNotFoundException
	 */
	public function testNoClassesMatch() {
		$reg   = new Registration('RequiresRootInterface', self::PROBLEM_PKG);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\RequiresRootInterface');
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\NoTypeDeclarationException
	 */
	public function testBareParameter() {
		$reg   = new Registration('HasNoStronglyTypedParameter', self::PROBLEM_PKG);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\HasNoStronglyTypedParameter');
	}

	private function _setupSecondClass() {
		$reg   = new Registration('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);
	}
}

