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
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

		$obj = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\SecondClass', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsByInterface() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

		$obj = $this->_factory->getObjectsByInterface(ltrim(self::SIMPLE_PKG.'\\MyInterface', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsBySuperClass() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

		$obj = $this->_factory->getObjectsBySuperClass(ltrim(self::SIMPLE_PKG.'\\FirstClass', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testGetObjectsByIdentifier() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

		$obj = $this->_factory->getObjectsByIdentifier('second');

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\SecondClass', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testSingleton() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

		$obj1 = $this->_factory->getObjectsBySuperClass(ltrim(self::SIMPLE_PKG.'\\FirstClass', '\\'));
		$obj2 = $this->_factory->getObjectsByInterface(ltrim(self::SIMPLE_PKG.'\\MyInterface', '\\'));

		$obj1[0]->x = 1;

		$this->assertEquals(1, $obj2[0]->x, 'The whole purpose of the system is broken.');
	}

	/**
	 * @test
	 */
	public function testNonSingleton() {
		$this->_setupClass('ThirdClass', self::SIMPLE_PKG, [Registration::SINGLETON => false]);

		$obj1 = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\ThirdClass', '\\'));
		$obj2 = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\ThirdClass', '\\'));

		$obj1[0]->x = 1;

		$this->assertNotEquals(1, $obj2[0]->x, 'The whole purpose of the system is broken.');
	}

	/**
	 * @test
	 */
	public function testStaticConstructor() {
		$this->_setupClass('HasStaticConstructor', self::SIMPLE_PKG, [Registration::CONSTRUCTOR => 'getInstance']);

		$obj = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\HasStaticConstructor');

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\HasStaticConstructor', $obj[0]);
	}

	/**
	 * @test
	 */
	public function testInstantiationWithLeadingSlash() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);

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
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);
		$this->_setupClass('HasParameters', self::SIMPLE_PKG);

		$objs = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\HasParameters');

		$this->assertCount(1, $objs);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\HasParameters', $objs[0]);
	}

	/**
	 * @test
	 */
	public function testClassInstantiationWithConfiguredParameters() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'this']);
		$this->_setupClass('ThirdClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'that']);
		$this->_setupClass('HasMultipleParameters', self::SIMPLE_PKG, [Registration::PARAMETERS => ['&this', '&that', 'string', 1, ['x', 'y', 'z']]]);

		$objs = $this->_factory->getObjectsByClassName(self::SIMPLE_PKG.'\\HasMultipleParameters');

		$this->assertCount(1, $objs);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\HasMultipleParameters', $objs[0]);
		$this->assertEquals('string', $objs[0]->string);
		$this->assertEquals(1, $objs[0]->integer);
		$this->assertEquals(['x', 'y', 'z'], $objs[0]->array);
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\CircularDependencyException
	 */
	public function testCircularDependency() {
		$classes = ['A', 'B', 'C'];
		foreach ($classes as $class) {
			$this->_setupClass($class, self::PROBLEM_PKG);
		}

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\A');
	}

	/**
	 * @test
	 */
	public function testConfiguredParameters() {
		$this->_setupClass('SecondClass', self::SIMPLE_PKG, [Registration::IDENTIFIER => 'second']);
		$this->_setupClass('ThirdClass', self::SIMPLE_PKG, [Registration::SINGLETON => false]);
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\TooManyClassesException
	 */
	public function testTooManyClassesMatch() {
		$classes = ['FirstImplementation', 'SecondImplementation', 'RequiresRootInterface'];
		foreach ($classes as $class) {
			$this->_setupClass($class, self::PROBLEM_PKG);
		}

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\RequiresRootInterface');
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\ClassNotFoundException
	 */
	public function testNoClassesMatch() {
		$this->_setupClass('RequiresRootInterface', self::PROBLEM_PKG);

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\RequiresRootInterface');
	}

	/**
	 * @test
	 * @expectedException \Inverted\Core\NoTypeDeclarationException
	 */
	public function testBareParameter() {
		$this->_setupClass('HasNoStronglyTypedParameter', self::PROBLEM_PKG);

		$objs = $this->_factory->getObjectsByClassName(self::PROBLEM_PKG.'\\HasNoStronglyTypedParameter');
	}

	private function _setupClass($class, $namespace, $options=[]) {
		$reg   = new Registration($class, $namespace, $options);
		$ssalc = new RegisteredClass($reg);

		$this->_factory->addClassToRegistry($ssalc);
	}

	/**
	 * @test
	 */
	public function testAutoloading() {
		$obj = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\FifthClass', '\\'));

		$this->assertCount(1, $obj);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\FifthClass', $obj[0]);

		$third = $this->_factory->getObjectsByClassName(ltrim(self::SIMPLE_PKG.'\\ThirdClass', '\\'));
		$this->assertCount(1, $third);
		$this->assertInstanceOf(self::SIMPLE_PKG.'\\ThirdClass', $third[0]);
		$this->assertEquals($obj[0]->three->x, $third[0]->x);
	}
}
