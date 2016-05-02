<?php
namespace Inverted\Core {

	/**
	 *
	 */
	class ObjectFactory extends ClassRegistry {
		/**
		 * @var InstantiatedObject[]
		 */
		private $cache;

		/**
		 * @var integer[]
		 */
		private $to_resolve;

		public function __construct() {
			parent::__construct();

			$this->cache = [];
			$this->to_resolve = [];
		}

		/**
		 *
		 */
		public function getObjectsByClassName($class_name) {
			$objects   = [];
			$positions = $this->_indices(self::BY_CLASS_NAME, $class_name);
			foreach ($positions as $position) {
				if (isset($this->cache[$position]) && !empty($this->cache[$position])) {
					$objects[] = $this->cache[$position];
				} else {
					$this->_resolve($position);
				}
			}
		}

		/**
		 *
		 */
		public function getObjectsByInterface($interface) {
			$objects   = [];
			$positions = $this->_indices(self::BY_CLASS_NAME, $class_name);
			foreach ($positions as $position) {

			}
		}

		/**
		 *
		 */
		public function getObjectsBySuperClass($superclass) {
			$objects   = [];
			$positions = $this->_indices(self::BY_CLASS_NAME, $class_name);
			foreach ($positions as $position) {

			}
		}

		// 
		private function _indices($thing, $index) {
			$indices = $this->indices[$index][$thing];
			return (empty($indices)) ? [] : $indices;
		}

		//
		private function _resolve($position) {
			if (in_array($position, $this->to_resolve)) {
				throw new CircularDependencyException();
			}
			$this->to_resolve[] = $position;
		}
	}
}
