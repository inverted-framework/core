<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\InstantiatedObject;

/**
 * 
 */
class InstantiatedObjectTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 * @expectedException Inverted\Core\EmptyObjectException
	 */
	public function testNullClass() {
		$obj = new InstantiatedObject(null);
	}

	/**
	 * @test
	 * @expectedException Inverted\Core\NonObjectException
	 */
	public function testNotAnObject() {
		$obj = new InstantiatedObject('not an object');
	}

	/**
	 * @test
	 */
	public function testCreation() {
		$fst = new \Inverted\Core\Tests\Projects\Simple\FirstClass();
		$obj = new InstantiatedObject($fst);
		$reg = $obj->getRegisteredClass();

		$this->assertEquals($fst, $obj->getInstance());
		$this->assertEquals('Inverted\Core\Tests\Projects\Simple\FirstClass', $reg->getClass());
	}
}
