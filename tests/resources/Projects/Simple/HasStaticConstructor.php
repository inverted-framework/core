<?php

namespace Inverted\Core\Tests\Projects\Simple;

/**
 * 
 */
class HasStaticConstructor
{
    public static function GetInstance() {
    	return new HasStaticConstructor();   
    }
    
    private function __construct() {
        
    }
}
