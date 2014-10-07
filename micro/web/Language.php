<?php /** MicroLanguage */

namespace Micro\web;

use \Micro\base\Exception;
use \Micro\base\Registry;

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
     * @param string $viewname  name of view
     * @result void
     * @throws Exception
     */
    public function __construct($viewname)
    {
        $lang = (Registry::get('lang')) ? Registry::get('lang') : $this->defaultLang;
        if (!file_exists($viewname . $lang . '.ini')) {
            throw new Exception('Language file ' . $viewname . $lang . '.ini not exists.');
        }
        $this->language = parse_ini_file($viewname . 'ini', true);
    }

    /**
     * Get param value
     *
     * @access public
     * @param string $name element name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->language[$name];
    }
}