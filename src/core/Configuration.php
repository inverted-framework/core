<?php
namespace Inverted\Core {
	/**
	 *
	 */
	class Configuration {
		private $_namespace;

		public function setNamespace($ns) {
			$this->_namespace = $ns;
		}

		public function getNamespace() {
			return $this->_namespace;
		}
	}
}

/*
{
	"namespace": "\\Full\\Path\\To\\Namespace",
	"classes": [
		{
			"class_name": "Class",
			"name": "identifier"
			"constructor": "GetInstance",
			"parameters": ["string", "&other_name", 1, ]
		},
		{
			"class_name": "\\Another\\Path\\To\\Namespace\\Class",
			"name": "identifier2",
			"singleton": false
		}
	]
}
*/