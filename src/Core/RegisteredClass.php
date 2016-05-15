<?php
namespace Inverted\Core;

/**
 *
 */
class RegisteredClass {
	/**
	 * @var \ReflectionClass
	 */
	private $_ssalc;

	/**
	 * @var Registration
	 */ 
	private $_config;

	/**
	 *
	 */
	public function __construct(Registration $config) {
		$this->_ssalc = new \ReflectionClass($config->getClassName());
		$this->_config = $config;
	}

	/**
	 *
	 */
	public function getRegistration() {
		return $this->_config;
	}

	/**
	 *
	 */
	public function getClass() {
		return $this->_ssalc->getName();
	}

	/**
	 *
	 */
	public function getInterfaces() {
		return $this->_ssalc->getInterfaceNames();
	}

	/**
	 *
	 */
	public function getSuperClasses() {
		return $this->_get_super_classes($this->_ssalc);
	}

	// 
	private function _get_super_classes($obj) {
		$newObj    = $obj->getParentClass();
		$ancestors = [];

		if (!empty($newObj)) {
			$ancestors = $this->_get_super_classes($newObj);
			array_unshift($ancestors, $newObj->getName());
		}

		return $ancestors;
	}
}


