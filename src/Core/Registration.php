<?php
namespace Inverted\Core;

/**
 * 
 */
class Registration {
	const IDENTIFIER  = 'name';
	const CONSTRUCTOR = 'constructor';
	const PARAMETERS  = 'parameters';
	const SINGLETON   = 'singleton';

	/**
	 * @var array
	 */
	private $_defaults = [
		self::IDENTIFIER  => null,
		self::CONSTRUCTOR => null,
		self::PARAMETERS  => [],
		self::SINGLETON   => true,
	];

	/**
	 * @var string
	 */
	private $_class;

	/**
	 * @var array
	 */
	private $_data;

	/**
	 *
	 */
	public function __construct($class, $namespace='', $information=[]) {
		$this->_class = (!empty($namespace) && !StringUtil::startsWith($class, '\\')) ? $namespace . '\\' . $class : $class;
		$this->_data  = array_merge($this->_defaults, $information);
	}

	/**
	 *
	 */
	public function isValid() {
		return (!(empty($this->_class) || StringUtil::endsWith($this->_class, '\\')));
	}

	/**
	 *
	 */
	public function getClassName() {
		return $this->_class;
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
}
