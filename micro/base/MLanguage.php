<?php /** MicroLanguage */

/**
 * MLanguage getter language tags from *.ini files
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MLanguage
{
	/** @var array $language language array */
	private $language = array();

	private $defaultLang = 'en';


	/**
	 * Constructor language
	 *
	 * @access public
	 * @param string $filename
	 * @result void
	 * @throws MException
	 */
	public function __construct($filename) {

		$lang = (!empty(MRegistry::get('lang'))) ? MRegistry::get('lang') : $this->defaultLang;
		if (!file_exists($filename.$lang.'.ini')) {
			throw new MException('Language file '.$filename.$lang.'.ini not exists.');
		}
		$this->language = parse_ini_file($filename.'ini', true);
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