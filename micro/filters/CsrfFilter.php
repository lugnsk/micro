<?php /** CsrfFilterMicro */

namespace Micro\filters;

use Micro\base\Registry;

/**
 * Class CrsfFilter
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
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
     * @global      Registry
     *
     * @param array $params checked items and other params
     *
     * @return boolean
     */
    public function pre(array $params)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        if (empty($_POST['csrf'])) {
            $this->result = [
                'redirect' => !empty($rule['redirect']) ? $rule['redirect'] : null,
                'message'  => !empty($rule['message']) ? $rule['message'] : 'Not allowed!'
            ];
            return false;
        }

        $csrf = Registry::get('session')->csrf;
        if (($key = in_array($_POST['csrf'], $csrf, true)) !== null) {

            unset($csrf[$key], $_POST['csrf']);
            Registry::get('session')->csrf = $csrf;
            return true;
        }
        $this->result = [
            'redirect' => !empty($rule['redirect']) ? $rule['redirect'] : null,
            'message'  => !empty($rule['message']) ? $rule['message'] : 'Bad request!'
        ];
        return false;
    }

    /**
     * PostFilter
     *
     * @access public
     * @global      Registry
     *
     * @param array $params checked items and other params
     *
     * @return mixed
     */
    public function post(array $params)
    {
        return preg_replace_callback('/(<form[^>]*>)(.*?)(<\/form>)/m',
            create_function('$matches', '$gen = md5(rand()); $s = Micro\base\Registry::get("session"); ' .
                '$arr = $s->csrf; $arr[] = md5($gen); $s->csrf = $arr; return $matches[1]."<input type=\"hidden\"' .
                ' name=\"csrf\" value=\"".$gen."\" />".$matches[2].$matches[3];'
            ),
            $params['data']
        );
    }
}