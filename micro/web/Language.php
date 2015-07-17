<?php /** MicroLanguage */

namespace Micro\web;

use Micro\base\Container;
use Micro\base\Exception;

/**
 * Language getter language tags from *.ini files
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Language extends \stdClass
{
    /** @var Container $container */
    protected $container;
    /** @var array $language language array */
    private $language = [];
    /** @var string $defaultLang default language */
    private $defaultLang = 'en';

    /**
     * Constructor language
     *
     * @access public
     *
     * @param Container $container
     * @param string $viewNameFile path to view
     *
     * @result void
     */
    public function __construct(Container $container, $viewNameFile)
    {
        $this->container = $container;

        $viewName = substr($viewNameFile, 0, -3);

        $lang = $this->container->lang;
        $lang = $lang ?: $this->defaultLang;

        if (!file_exists($viewName . $lang . '.ini')) {
            return;
        }

        $this->language = parse_ini_file($viewName . $lang . '.ini', true);
    }

    /**
     * Get param value
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (!empty($this->language[$name])) {
            return $this->language[$name];
        } else {
            throw new Exception($this->container, $name . ' not defined into lang file');
        }
    }
}