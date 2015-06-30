<?php /** MicroRouter */

namespace Micro\web;

/**
 * Router class file.
 *
 * Routing user requests
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class Router
{
    /** @var array $routes routes for routing */
    public $routes = [];


    /**
     * Construct for route scanner
     *
     * @access public
     *
     * @param array $config
     *
     * @result void
     */
    public function __construct(array $config = [])
    {
        $this->routes = $config['routes'];
    }

    /**
     * Parsing uri
     *
     * @access public
     *
     * @param string $uri current check URI
     * @param string $method current Request method
     *
     * @return string
     */
    public function parse($uri, $method = 'GET')
    {
        // default path
        if ($uri === '/' || $uri === '' || $uri === '/default') {
            return '/default';
        }

        // scan routes
        foreach ($this->routes AS $condition => $config) {
            if (is_array($config) && !empty($config['route'])) {
                if (!empty($config['verb']) && ($config['verb'] !== $method)) {
                    continue;
                }
                $replacement = $config['route'];
            } elseif (is_string($config)) {
                $replacement = $config;
            } else {
                continue;
            }
            // slice path
            if ($uri === $condition) {
                return $replacement;
            }
            // pattern path
            if ($validated = $this->validatedRule($uri, $condition, $replacement)) {
                return $validated;
            }
        }
        // return raw uri
        return $uri;
    }

    /**
     * Validated router rule
     *
     * @access private
     *
     * @param string $uri uri to validation
     * @param string $pattern checking pattern
     * @param string $replacement replacement for pattern
     *
     * @return string
     */
    private function validatedRule($uri, $pattern, $replacement)
    {
        $uriBlocks = explode('/', trim($uri, '/'));
        $patBlocks = explode('/', trim($pattern, '/'));
        $repBlocks = explode('/', trim($replacement, '/'));

        if (count($uriBlocks) !== count($patBlocks)) {
            return false;
        }

        $attributes = $this->parseUri($uriBlocks, $patBlocks);
        if (!$attributes) {
            return false;
        }

        $result = $this->buildResult($attributes, $repBlocks);
        if ($result === null || $result === false) {
            return false;
        }

        if (!$attributes) {
            return $result;
        }

        $result .= '?';
        foreach ($attributes AS $key => $val) {
            if ($key!==$val) {
                $result .= '&'.$key.'='.$val;
            }
        }

        return $result;
    }

    /**
     * Match patBlocks in uriBlocks
     *
     * @access private
     *
     * @param array $uriBlocks uri blocks from URL
     * @param array $patBlocks pattern blocks from valid URL
     *
     * @return array|bool
     */
    private function parseUri(array $uriBlocks = [], array $patBlocks = [])
    {
        $attr = [];

        $countUriBlocks = count($uriBlocks);
        for ($i = 0; $i < $countUriBlocks; $i++) {
            if ($patBlocks[$i]{0} === '<') {
                $cut = strpos($patBlocks[$i], ':');

                if (preg_match('/' . substr($patBlocks[$i], $cut + 1, -1) . '/', $uriBlocks[$i])) {
                    $attr[substr($patBlocks[$i], 1, $cut - 1)] = $uriBlocks[$i];
                } else {
                    return false;
                }

            } elseif ($uriBlocks[$i] !== $patBlocks[$i]) {
                return false;
            } else {
                $attr[$uriBlocks[$i]] = $patBlocks[$i];
            }
        }
        return $attr;
    }

    /**
     * Replacement $result with repBlocks
     *
     * @access private
     *
     * @param array $attr elements of valid URL
     * @param array $repBlocks replacement blocks from valid URL
     *
     * @return bool|null|string
     */
    private function buildResult(&$attr, $repBlocks)
    {
        $result = null;
        foreach ($repBlocks AS $value) {
            if ($value{0} !== '<') {
                $result .= '/' . $value;
                unset($attr[$value]);
            } else {
                $element = substr($value, 1, -1);
                if (!empty($attr[$element])) {
                    $result .= '/' . $attr[$element];
                    unset($attr[$element]);
                } else {
                    return false;
                }
            }
        }

        return $result;
    }
}
