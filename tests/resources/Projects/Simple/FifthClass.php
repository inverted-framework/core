<?php

namespace Inverted\Core\Tests\Projects\Simple;

/**
 *
 */
class FifthClass
{
	public $three;
	
	public function __construct(ThirdClass $three) {
		$three->x = 3;

		$this->three = $three;
	}
}
