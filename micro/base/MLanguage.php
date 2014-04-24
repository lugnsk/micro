<?php

/**
 * MLanguage getter language tags from *.ini files
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MLanguage
{
	/** @var array $language */
	private $language = array();


	/**
	 * Constructor language
	 *
	 * @access public
	 * @param string $filename
	 * @result void
	 */
	public function __construct($filename) {
		$this->language = parse_ini_file($filename, true);
	}
	/**
	 * Get param value
	 *
	 * @access public
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->language[$name];
	}
}