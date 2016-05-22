<?php
namespace Inverted\Core;

/**
 *
 */
class Configuration {
	const NAMESPACE     = 'namespace';
	const INCLUDE_DECL  = 'include';
	const REGISTRATIONS = 'classes';

	/**
	 * @var string
	 */
	private $_namespace;

	public function __construct() {
		$this->_namespace = '';
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

