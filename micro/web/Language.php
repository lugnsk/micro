<?php /** MicroLanguage */

namespace Micro\web;

use \Micro\base\Exception;

/**
 * Language getter language tags from *.ini files
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Language
{
    /** @var array $language language array */
    private $language = [];
    /** @var string $defaultLang default language  */
    private $defaultLang = 'en';


    /**
     * Constructor language
     *
     * @access public
     * @param string $filename
     * @result void
     * @throws Exception
     */
    public function __construct($filename)
    {
        $lang = (Registry::get('lang')) ? Registry::get('lang') : $this->defaultLang;
        if (!file_exists($filename . $lang . '.ini')) {
            throw new Exception('Language file ' . $filename . $lang . '.ini not exists.');
        }
        $this->language = parse_ini_file($filename . 'ini', true);
    }

    /**
     * Get param value
     *
     * @access public
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->language[$name];
    }
}