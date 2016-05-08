<?php
namespace Inverted\Core;

/**
 *
 */
class Configuration {
	const NAMESPACE     = 'namespace';
	const REGISTRATIONS = 'classes';

	/**
	 * @var Configuration
	 */
	private static $_INSTANCE;

	/**
	 * @var string
	 */
	private $_namespace;

	private function __construct() {
		$this->_namespace = '';
	}

	public static function getInstance() {
		if (empty(self::$_INSTANCE)) {
			self::$_INSTANCE = new Configuration();
		}
		return self::$_INSTANCE;
	}

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
}

