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
		return $this->_get_objects($interface, self::BY_INTERFACE);
	}

	/**
	 *
	 */
	public function getObjectsBySuperClass($superclass) {
		return $this->_get_objects($superclass, self::BY_SUPERCLASS);
	}

	/**
	 *
	 */
	public function getObjectsByIdentifier($identifier) {
		return $this->_get_objects($identifier, self::BY_IDENTIFIER);
	}

	// This class returns an array of objects (or empty array) of things specified.
	// $index is which index to search through (enumerated by the constants in ClassRegistry)
	// $thing is the class/interface/identifier to search for.
	private function _get_objects($thing, $index) {
		$objects   = [];
		$positions = $this->_indices($thing, $index);
		foreach ($positions as $position) {
			$objects[] = $this->_resolve($position)->getInstance();
		}
		return $objects;
	}

	// Returns an array (if empty) of matching positions in an index.
	private function _indices($thing, $index) {
		$thing   = ltrim($thing, '\\');
		return (isset($this->indices[$index][$thing])) ? $this->indices[$index][$thing] : [];
	}

	private function _search($type, $identifier) {
		$identifiers = $this->_indices($identifier, self::BY_IDENTIFIER);
		$indexes     = [self::BY_CLASS_NAME, self::BY_INTERFACE, self::BY_SUPERCLASS];

		foreach ($indexes as $index) {
			$types = $this->_indices($type, $index);
			if (count($types) > 0) {
				break;
			}
		}
		
		if (!empty($identifiers)) {
			return array_intersect($identifiers, $types);
		} else {
			return $types;
		}
	}

	//
	private function _resolve($position) {
		if (in_array($position, $this->_stack)) {
			throw new CircularDependencyException();
		}
		$this->_stack[] = $position;

		if (!isset($this->_cache[$position]) || empty($this->_cache[$position])) {
			$ssalc = $this->registry[$position];

			// TODO: Check if class has configured parameters and use those instead.
			$params = $this->_get_parameters($ssalc->getConstructor());
			$args = [];
			foreach ($params as $param) {
				if ($param->getClass()) {
					$results = $this->_search($param->getClass()->getName(), $param->getName());

					if (count($results) == 1) {
						$args[] = $this->_resolve($results[0])->getInstance();
					} else {
						if (count($results) == 0) {
							throw new ClassNotFoundException();
						} else {
							throw new TooManyClassesException();
						}
					}
				} else {
					throw new NoTypeDeclarationException();
				}
			}

			$object = $ssalc->Instantiate($args);
			if ($ssalc->isSingleton()) {
				$this->_cache[$position] = $object;
			}
		} else {
			$object = $this->_cache[$position];
		}

		array_pop($this->_stack);
		return $object;
	}

	// 
	private function _get_parameters($method) {
		return (!empty($method)) ? $method->getParameters() : [];
	}
}
