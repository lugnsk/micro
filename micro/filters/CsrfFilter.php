<?php /** CsrfFilterMicro */

namespace Micro\filters;

use Micro\base\Registry;

/**
 * Class CrsfFilter
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filters
 * @version 1.0
 * @since 1.0
 */
class CsrfFilter extends Filter
{
    /**
     * PreFilter
     *
     * @access public
     * @global Registry
     * @param array $params checked items and other params
     * @return boolean
     */
    public function pre(array $params)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        if (!isset($_POST['csrf']) OR !$_POST['csrf']) {
            $this->result = 'Not allowed';
            return false;
        }

        $csrf = Registry::get('session')->csrf;
        if (($key = array_search($_POST['csrf'], $csrf)) !==NULL) {

            unset($csrf[$key], $_POST['csrf']);
            Registry::get('session')->csrf = $csrf;
            return true;
        }
        $this->result = 'Bad request';
        return false;
    }

    /**
     * PostFilter
     *
     * @access public
     * @global Registry
     * @param array $params checked items and other params
     * @return mixed
     */
    public function post(array $params)
    {
        return preg_replace_callback( '/(<form[^>]*>)(.*?)(<\/form>)/m',
            create_function( '$matches', '$gen = md5(rand()); $s = Micro\base\Registry::get("session"); '.
                '$arr = $s->csrf; $arr[] = md5($gen); $s->csrf = $arr; return $matches[1]."<input type=\"hidden\"'.
                ' name=\"csrf\" value=\"".$gen."\" />".$matches[2].$matches[3];'
            ),
            $params['data']
        );
    }
}