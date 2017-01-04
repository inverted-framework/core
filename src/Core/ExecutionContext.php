<?php
namespace Inverted\Core;

/**
 *
 */
class ExecutionContext extends ObjectFactory {
	public function __construct(Configuration $config) {
		parent::__construct($config->getUseAutoload());
		
		foreach ($config->getRegistrations() as $registration) {
			$ssalc = new RegisteredClass($registration);
			$this->addClassToRegistry($ssalc);
		}
	}
}

