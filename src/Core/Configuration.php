<?php
namespace Inverted\Core;

/**
 *
 */
class Configuration {
	const KEYWORD_NAMESPACE = 'namespace';
	const KEYWORD_INCLUDE   = 'include';
	const KEYWORD_CLASSES   = 'classes';

	/**
	 * @var string
	 */
	private $_namespace;

	/**
	 * @var bool
	 */
	private $_use_autoload;

	/**
	 * @var Registrations[]
	 */
	private $_registrations;

	public function __construct() {
		$this->_namespace     = '';
		$this->_use_autoload  = true;
		$this->_registrations = [];
	}

	/**
	 *
	 */
	public function addConfiguration(Configuration $config, $overwrite_registrations=false) {
		// $this->setNamespace($config->getNamespace());
		$this->setUseAutoload($config->getUseAutoload());

		if ($overwrite_registrations) {
			$this->setRegistrations($config->getRegistrations());
		} else {
			foreach ($config->getRegistrations() as $registration) {
				$this->addRegistration($registration);
			}
		}
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

	/**
	 *
	 */
	public function setUseAutoload($use_autoload) {
		$this->_use_autoload = $use_autoload;
	}

	/**
	 *
	 */
	public function getUseAutoload() {
		return $this->_use_autoload;
	}

	/**
	 *
	 */
	public function addRegistration($registration) {
		$this->_registrations[] = $registration;
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

