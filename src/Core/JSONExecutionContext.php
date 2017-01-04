<?php
namespace Inverted\Core;

/**
 *
 * An example of a configuration is below.
 *
 * 	{
 *		"namespace": "\\Full\\Path\\To\\Namespace",
 *		"include": "/path/to/abother/include.json",
 *		"classes": [
 *			{
 *				"class_name": "Class",
 *				"name": "identifier"
 *				"constructor": "GetInstance",
 *				"parameters": ["string", "&other_name", 1]
 *			},
 *			{
 *				"class_name": "\\Another\\Path\\To\\Namespace\\Class",
 *				"name": "identifier2",
 *				"singleton": false
 *			}
 *		]
 *	}
 */
class JSONExecutionContext extends ObjectFactory {
	public function __construct($configuration_file) {
		parent::__construct($this->_parse($configuration_file));
	}

	private function _parse($configuration_file) {
		if (!file_exists($configuration_file)) {
			throw new MissingConfigurationFileException();
		}

		// TODO: Support comments.
		$config = $this->_json_clean_decode(file_get_contents($configuration_file));
		if (empty($config)) {
			throw new InvalidConfigurationFileException();
		}

		$configuration = new Configuration();
		if (isset($config[Configuration::KEYWORD_INCLUDE])) {
			// TODO: Make whether we want to inherit a namespace configurable.
			$configuration->addConfiguration($this->_parse($config[Configuration::KEYWORD_INCLUDE]));
		}

		if (isset($config[Configuration::KEYWORD_NAMESPACE])) {
			$configuration->setNamespace($config[Configuration::KEYWORD_NAMESPACE])
		}

		if (isset($config[Configuration::KEYWORD_REGISTRATIONS])) {
			foreach ($config[Configuration::KEYWORD_REGISTRATIONS] as $registration) {
				$name  = $this->_array_key_read_delete($registration, Registration::CLASS_NAME);
				$reg   = new Registration($name, $configuration->getNamespace(), $registration);
				$configuration->addRegistration($reg);
			}
		}
	}

	private function _array_key_read_delete(&$array, $key) {
		$val = $array[$key];
		unset($array[$key]);
		return $val;
	}

	// Ripped off of http://php.net/manual/en/function.json-decode.php
	private function _json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
	    // search and remove comments like /* */ and //
	    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
	    
	    // TODO: Use ExecutionContext for version sniffing?
	    if(version_compare(phpversion(), '5.4.0', '>=')) {
	        $json = json_decode($json, $assoc, $depth, $options);
	    }
	    elseif(version_compare(phpversion(), '5.3.0', '>=')) {
	        $json = json_decode($json, $assoc, $depth);
	    }
	    else {
	        $json = json_decode($json, $assoc);
	    }

	    return $json;
	}
}

