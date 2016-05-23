<?php

namespace Inverted\Core\Tests\Projects\Simple;

/**
 * 
 */
class HasMultipleParameters
{
	/**
	 * @var Second
	 */
	public $second;

	/**
	 * @var Third
	 */
	public $third;

	/**
	 * @var string
	 */
	public $string;

	/**
	 * @var integer
	 */
	public $integer;

	/**
	 * @var array
	 */
	public $array;


	public function __construct(SecondClass $second, ThirdClass $third, $string, $integer, $array)
	{
		$this->second  = $second;
		$this->third   = $third;
		$this->string  = $string;
		$this->integer = $integer;
		$this->array   = $array;
	}
}
