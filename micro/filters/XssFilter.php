<?php /** XssFilterMicro */

namespace Micro\filters;

/**
 * Class XssFilter

 * @author  Opeykin A. <andrey.opeykin.ru> &; <aopeykin@gmail.com>
 * @package micro
 * @subpackage filters
 * @version 0.0.1
 */
class XssFilter implements Filter
{
    /**
     * PreFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return bool
     */
    public function pre(array $params) {
        $clean = trim(strtoupper( (isset($params['clean']) ? $params['clean'] : '*') ));
        $data = [
            'GET'   =>&$_GET,
            'POST'  =>&$_POST,
            'COOKIE'=>&$_COOKIE,
            'FILES' =>&$_FILES
        ];

        if ($clean == '*') {
            $clean = 'GET,POST,COOKIE,FILES';
        }
        $dataForClean = explode(',',$clean);

        if(count($dataForClean)) {
            foreach ($dataForClean as $key => $value) {
                if(isset ($data[$value]) && count($data[$value])) {
                    $this->doXssClean($data[$value]);
                }
            }
        }
        return true;
    }

    /**
     * PostFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return void
     */
    public function post(array $params) {
        // not
    }

    /**
     * Do XSS Clean
     *
     * @access private
     * @param $data data for check
     * @return mixed
     */
    private function doXssClean(&$data) {
        if(is_array($data) && count($data)) {
            foreach($data as $k => $v) {
                $data[$k] = $this->doXssClean($v);
            }
            return $data;
        }

        if(trim($data) === '') {
            return $data;
        }

        // xss_clean function from Kohana framework 2.3.1
        $data = str_replace(['&amp;','&lt;','&gt;'], ['&amp;amp;','&amp;lt;','&amp;gt;'], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        return $data;
    }
}