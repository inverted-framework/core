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

	/**
	 * @var boolean
	 */
	protected $_use_variadic;

	/**
	 * @var boolean
	 */
	protected $_attempt_autoload;

	public function __construct($attempt_autoload=true) {
		parent::__construct();

		$this->_cache = [];
		$this->_stack = [];

		$this->_use_variadic     = false;
		$this->_attempt_autoload = $attempt_autoload;
	}

	/**
	 *
	 */
	public function getObjectsByClassName($class_name) {
		$objects = $this->_get_objects($class_name, self::BY_CLASS_NAME);
		if (count($objects) == 0) {
			if ($this->_autoload_class($class_name)) {
				$objects = $this->_get_objects($class_name, self::BY_CLASS_NAME);
			}
		}
		return $objects;
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

			$parameters = $ssalc->getParameters();
			if (empty($parameters)) {
				$args = $this->_resolve_method_parameters($this->_get_parameters($ssalc->getConstructor()));
			} else {
				$args = $this->_resolve_configured_parameters($parameters);
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

	private function _resolve_method_parameters($params) {
		$args = [];
		foreach ($params as $param) {
			if ($param->getClass()) {
				$results = $this->_search($param->getClass()->getName(), $param->getName());

				switch (count($results)) {
					case 0:
						if ($this->_autoload_class($param->getClass()->getName())) {
							$results = $this->_search($param->getClass()->getName(), $param->getName());
						}
						// At this point, it is not possible to have count($results) > 0.
						if (count($results) != 1) {
							throw new ClassNotFoundException();
							break;
						}
					case 1:
						$args[] = $this->_resolve($results[0])->getInstance();
						break;
					default:
						throw new TooManyClassesException();
				}
			} else {
				throw new NoTypeDeclarationException();
			}
		}
		return $args;
	}

	private function _resolve_configured_parameters($params) {
		$args = [];
		foreach ($params as $param) {
			if (is_string($param) && StringUtil::startsWith($param, '&')) {
				$objs = $this->getObjectsByIdentifier(substr($param, 1));
				if (!empty($objs)) {
					$args[] = $objs[0];
				} else {
					throw new IdentifierNotFoundException();
				}
			} else {
				$args[] = $param;
			}
		}
		return $args;
	}

	// class_exists will force PHP to attempt to autoload the class in question.
	private function _autoload_class($class_name) {
		if ($this->_attempt_autoload && class_exists($class_name)) {
			$reg   = new Registration($class_name);
			$ssalc = new RegisteredClass($reg);
			$this->addClassToRegistry($ssalc);
			return true;
		}
		return false;
	}
}
