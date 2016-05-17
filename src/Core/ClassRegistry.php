<?php
namespace Inverted\Core;

/**
 *
 */
class ClassRegistry {
	const BY_CLASS_NAME = 'class';
	const BY_INTERFACE  = 'interface';
	const BY_SUPERCLASS = 'superclass';
	const BY_IDENTIFIER = 'identifier';

	/**
	 * @var RegisteredClass[]
	 */
	protected $registry;

	/**
	 * @var array
	 */ 
	protected $indices;

	/**
	 * @var number
	 */
	protected $current;

	/**
	 *
	 */
	public function __construct() {
		$this->current  = 0;

		$this->registry = [];
		$this->indices  = [];

		$this->indices[self::BY_CLASS_NAME] = [];
		$this->indices[self::BY_INTERFACE]  = [];
		$this->indices[self::BY_SUPERCLASS] = [];
		$this->indices[self::BY_IDENTIFIER] = [];
	}

	/**
	 *
	 */
	public function addClassToRegistry(RegisteredClass &$class) {
		$this->registry[$this->current] = $class;

		$this->_index(self::BY_CLASS_NAME, [$class->getClass()]);
		$this->_index(self::BY_INTERFACE,  $class->getInterfaces());
		$this->_index(self::BY_SUPERCLASS, $class->getSuperClasses());

		$id = $class->getIdentifier();
		if (!empty($id)) {
			$this->_index(self::BY_IDENTIFIER, [$id]);
		}

		$this->current++;
	}

	/**
	 *
	 */
	public function getClassesByClassName($class_name) {
		return $this->_retrieve($class_name, self::BY_CLASS_NAME);
	}

	/**
	 *
	 */
	public function getClassesByInterface($interface) {
		return $this->_retrieve($interface, self::BY_INTERFACE);
	}

	/**
	 *
	 */
	public function getClassesBySuperClass($superclass) {
		return $this->_retrieve($superclass, self::BY_SUPERCLASS);
	}

	/**
	 *
	 */
	public function getClassesByIdentifier($identifier) {
		return $this->_retrieve($identifier, self::BY_IDENTIFIER);
	}

	//
	private function _index($index, $array_of_index_keys) {
		foreach ($array_of_index_keys as $ik) {
			if (isset($this->indices[$index][$ik])) {
				$this->indices[$index][$ik][] = $this->current;
			} else {
				$this->indices[$index][$ik] = [$this->current];
			}
		}
	}

	//
	private function _retrieve($thing, $index) {
		$thing    = ltrim($thing, '\\');
		$registry = &$this->registry;

		$fn = function ($position) use ($registry) {
			return $registry[$position];
		};

		return array_map($fn, $this->indices[$index][$thing]);
	}
}

