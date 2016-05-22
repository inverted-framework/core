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

	public function Instantiate($arguments) {
		if ($this->_config->getConstructor() === null) {
			$raw = $this->_ssalc->newInstanceArgs($arguments);
		} else {
			$method = $this->_ssalc->getMethod($this->_config->getConstructor());
			$raw    = $method->invokeArgs(null, $arguments);
		}
		
		return new InstantiatedObject($raw);
	}

	public function getConstructor() {
		$method_name = $this->_config->getConstructor();
		if (! empty($method_name)) {
			$method = $this->_ssalc->getMethod($method_name);
		} else {
			$method = $this->_ssalc->getConstructor(); 
		}
		return $method;
	}

	/**
	 *
	 */
	public function getIdentifier() {
		return $this->_config->getIdentifier();
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

	/**
	 *
	 */
	public function isSingleton() {
		return $this->_config->isSingleton();
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


