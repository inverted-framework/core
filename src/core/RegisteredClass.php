<?php
namespace Inverted\Core {

	/**
	 *
	 */
	class RegisteredClass {
		/**
		 * @var \ReflectionClass
		 */
		private $_ssalc;

		/**
		 *
		 */
		public function __construct($class_or_object, $is_reflection_class = false) {
			if (is_object($class_or_object)) {
				$class_name = get_class($class_or_object);
				if ($class_name == '\ReflectionClass' && $is_reflection_class) {
					$this->_ssalc = $class_or_object;
				} else {
					$this->_instance = $class_or_object;
					$this->_ssalc    = new \ReflectionClass($class_or_object);
				}
			} elseif (is_string($class_or_object) && class_exists($class_or_object)) {
				$this->_ssalc = new \ReflectionClass($class_or_object);
			} else {
				throw new ClassNotFoundException();
			}
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
}

