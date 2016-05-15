<?php
namespace Inverted\Core;

/**
 *
 */
class InstantiatedObject {
	/**
	 * @var mixed
	 */
	private $_instance;

	public function __construct($any_object) {
		if (empty($any_object)) {
			throw new EmptyObjectException();
		} elseif (!is_object($any_object)) {
			throw new NonObjectException();
		}

		$this->_instance = $any_object;
	}

	/**
	 *
	 */
	public function getInstance() {
		return $this->_instance;
	}

	/**
	 *
	 */
	public function getRegisteredClass() {
		$ssalc = new \ReflectionClass($this->_instance);
		return new RegisteredClass(new Registration($ssalc->getName()));
	}
}