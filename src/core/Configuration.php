<?php
namespace Inverted\Core {
	/**
	 *
	 */
	class Configuration {
		/**
		 * @var string
		 */
		private $_namespace;

		/**
		 * @var Registration[]
		 */
		private $_registrations;

		/**
		 *
		 */
		public function setNamespace($ns) {
			$this->_namespace = $ns;
		}

		/**
		 *
		 */
		public function getNamespace() {
			return $this->_namespace;
		}

		/**
		 *
		 */
		public function setRegistrations($registrations) {
			$this->_registrations = $registrations;
		}

		/**
		 *
		 */
		public function getRegistrations() {
			return $this->_registrations;
		}
	}
}
