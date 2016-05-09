<?php
namespace Inverted\Core;

/**
 *
 */
class ObjectFactory extends ClassRegistry {
	/**
	 * @var InstantiatedObject[]
	 */
	private $_cache;

	/**
	 * @var integer[]
	 */
	private $_stack;

	public function __construct() {
		parent::__construct();

		$this->_cache = [];
		$this->_stack = [];
	}

	/**
	 *
	 */
	public function getObjectsByClassName($class_name) {
		return $this->_get_objects($class_name, self::BY_CLASS_NAME);
	}

	//
	/**
	 *
	 */
	public function getObjectsByInterface($interface) {
		return $this->_get_objects($class_name, self::BY_INTERFACE);
	}

	/**
	 *
	 */
	public function getObjectsBySuperClass($superclass) {
		return $this->_get_objects($class_name, self::BY_SUPERCLASS);
	}

	// 
	private function _get_objects($thing, $index) {
		$objects   = [];
		$positions = $this->_indices($thing, $index);
		foreach ($positions as $position) {
			if (isset($this->_cache[$position]) && !empty($this->_cache[$position])) {
				$objects[] = $this->_cache[$position];
			} else {
				$objects[] = $this->_resolve($position);
			}
		}
		return $objects;
	}

	// 
	private function _indices($thing, $index) {
		$indices = $this->indices[$index][$thing];
		return (empty($indices)) ? [] : $indices;
	}

	//
	private function _resolve($position) {
		if (in_array($position, $this->_stack)) {
			throw new CircularDependencyException();
		}
		$this->_stack[] = $position;

		$ssalc = $this->registry[$position];

		// TODO: Check if class has configured parameters and use those instead.
		$params = $this->_get_parameters($ssalc->getConstructor());
		$args = [];
		foreach ($params as $param) {
			// TODO: Study signature and recurse as necessary.
		}

		array_pop($this->_stack);
		$object = $ssalc->newInstanceArgs($args);
		// TODO: Check if class is singleton, and optionally _cache.
		$this->_cache[$position] = $object;
		return $object;
	}

	// 
	private function _get_parameters($method) {
		return (!empty($method)) ? [] : $method->getParameters();
	}
}