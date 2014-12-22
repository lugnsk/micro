<?php /** MicroLanguage */

namespace Micro\web;

use Micro\base\Exception;
use Micro\Micro;

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
 * @property-read
 */
class Language
{
    /** @var array $language language array */
    private $language = [];
    /** @var string $defaultLang default language */
    private $defaultLang = 'en';


    /**
     * Constructor language
     *
     * @access public
     * @param string $viewNameFile path to view
     * @result void
     */
    public function __construct($viewNameFile)
    {
        $viewName = substr($viewNameFile, 0, -3);
        $config = Micro::getInstance()->config;
        $lang = (isset($config['lang'])) ? $config['lang'] : $this->defaultLang;

        if (!file_exists($viewName . $lang . '.ini')) {
            return;
        }

        $this->language = parse_ini_file($viewName . $lang . '.ini', true);
    }

    /**
     * Get param value
     *
     * @access public
     * @param string $name element name
     * @throws Exception
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->language[$name])) {
            return $this->language[$name];
        } else {
            throw new Exception($name . ' not defined into lang file');
        }
    }
}