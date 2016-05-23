<?php
namespace Inverted\Core\Tests;

use \Inverted\Core\ExecutionContext;

/**
 * 
 */
class ExecutionContextTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function testNoTests() {
		$this->markTestIncomplete('Dude.');
	}
}

