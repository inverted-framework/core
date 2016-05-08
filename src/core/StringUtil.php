<?php
namespace Inverted\Core;

/**
 * 
 */
class StringUtil {
	/**
	 * 
	 */
	public static function startsWith($string, $character) {
		return (substr($string, 0, 1) == $character);
	}

	/**
	 * 
	 */
	public static function endsWith($string, $character) {
		return (substr($string, -1) == $character);
	}
}
