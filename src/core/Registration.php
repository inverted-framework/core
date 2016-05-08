<?php
namespace Inverted\Core {

	/**
	 * 
	 */
	class Registration {
		const CLASS_NAME  = 'class_name';
		const IDENTIFIER  = 'name';
		const CONSTRUCTOR = 'constructor';
		const PARAMETERS  = 'parameters';
		const SINGLETON   = 'singleton';

		/**
		 * @var array
		 */
		private $_defaults = [
			self::CONSTRUCTOR => null,
			self::PARAMETERS  => [],
			self::SINGLETON   => true
		];

		/**
		 * @var array
		 */
		private $_data;

		/**
		 *
		 */
		public function __construct($information) {
			$this->_data = array_merge($this->_defaults, $information);
			$this->_normalize($namespace);
			$this->_validate();
		}

		/**
		 *
		 */
		public function getClassName() {
			return $this->_data[self::CLASS_NAME];
		}

		/**
		 *
		 */
		public function getIdentifier() {
			return $this->_data[self::IDENTIFIER];
		}

		/**
		 *
		 */
		public function getConstructor() {
			return $this->_data[self::CONSTRUCTOR];
		}

		/**
		 *
		 */
		public function getParameters() {
			return $this->_data[self::PARAMETERS];
		}

		/**
		 *
		 */
		public function isSingleton() {
			return $this->_data[self::SINGLETON];
		}

		private function _validate() {
			
		}

		private function _normalize($namespace) {
			// TODO: Combine namespace with classname to fully resolve the provided class.
			if (!empty($namespace) && !StringUtil::startsWith($this->getClassName(), '\\')) {
				$this->_data[self::CLASS_NAME] = $namespace . '\\' . $this->_data[self::CLASS_NAME];
			}
		}
	}
}