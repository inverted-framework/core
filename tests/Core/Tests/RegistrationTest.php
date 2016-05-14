<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\Registration;

/**
 * 
 */
class RegistrationTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testNormalConstruction() {
		$reg = new Registration(
			'AnyClass',
			'\\Any\\Thing\\Will\\Do',
			[
				Registration::IDENTIFIER  => 'name',
				Registration::CONSTRUCTOR => 'getInstance',
				Registration::PARAMETERS  => ["string", "&other_name", 1],
				Registration::SINGLETON   => false,
			]
		);

		$this->assertEquals('\\Any\\Thing\\Will\\Do\\AnyClass', $reg->getClassName());
		$this->assertEquals('name', $reg->getIdentifier());
		$this->assertFalse($reg->isSingleton());

		$params = $reg->getParameters();

		$this->assertEquals('string', $params[0]);
		$this->assertEquals('&other_name', $params[1]);
		$this->assertEquals(1, $params[2]);
	}

	/**
	 * @test
	 */
	public function testDefaults() {
		$reg = new Registration('AnyClass');

		$this->assertEquals('AnyClass', $reg->getClassName());
		$this->assertNull($reg->getIdentifier());
		$this->assertNull($reg->getConstructor());
		$this->assertEmpty($reg->getParameters());
		$this->assertTrue($reg->isSingleton());
	}

	/**
	 * @test
	 */
	public function testNamespaceDiscarded() {
		$reg = new Registration('\\Alternate\\Namespace\\For\\ClassName', '\\Default\\Namespace');
		$this->assertEquals('\\Alternate\\Namespace\\For\\ClassName', $reg->getClassName());
	}

	/**
	 * @test
	 */
	public function testValidity() {
		$reg = new Registration('', '');
		$this->assertFalse($reg->isValid());

		$reg = new Registration('Test', '\\AnyNameSpace\\');
		$this->assertTrue($reg->isValid());

		$reg = new Registration('', '\\AnyNameSpace\\');
		$this->assertFalse($reg->isValid());

		$reg = new Registration('Test', '\\AnyNameSpace\\');
		$this->assertFalse($reg->isValid(true));

		$reg = new Registration('Test', '\\AnyNameSpace\\');
		$this->assertFalse($reg->isValid(true));
	}
}

