<?php
namespace Inverted\Core {

	/**
	 *
	 */
	class ClassRegistry {
		const BY_CLASS_NAME = 'class';
		const BY_INTERFACE  = 'interface';
		const BY_SUPERCLASS = 'superclass';

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

		public function __construct() {
			$this->current  = 0;

			$this->registry = [];
			$this->indices  = [];

			$this->indices[self::BY_CLASS_NAME] = [];
			$this->indices[self::BY_INTERFACE]  = [];
			$this->indices[self::BY_SUPERCLASS] = [];
		}

		public function addClassToRegistry($name) {
			$class = new RegisteredClass($class);

			$this->registry[$this->current] = $class;

			$this->_index(self::BY_CLASS_NAME, [$class->getClassName()]);
			$this->_index(self::BY_INTERFACE,  $class->getInterfaces());
			$this->_index(self::BY_SUPERCLASS, $class->getSuperClasses());

			$this->current++;
		}

		/**
		 *
		 */
		public function getClassesByClassName($class_name) {
			return $this->_retrieve($superclass, self::BY_CLASS_NAME);
		}

		/**
		 *
		 */
		public function getClassesByInterface($interface) {
			return $this->_retrieve($superclass, self::BY_INTERFACE);
		}

		/**
		 *
		 */
		public function getClassesBySuperClass($superclass) {
			return $this->_retrieve($superclass, self::BY_SUPERCLASS);
		}


		//
		private function _index($index, $array_of_index_keys) {
			foreach ($array_of_index_keys as $ik) {
				if (is_array($this->indices[$index][$ik])) {
					$this->indices[$index][$ik][] = $this->current;
				}
			}
		}

		//
		private function _retrieve($thing, $index) {
			$registry = &$this->registry;

			$fn = function ($position) use (&$registry) {
				return $registry[$position];
			};

			return array_map($fn, $this->indices[$index][$thing]);
		}
	}
}
