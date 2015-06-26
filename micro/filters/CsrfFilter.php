<?php /** CsrfFilterMicro */

namespace Micro\filters;

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
        if ($this->container->request->getServerVar('REQUEST_METHOD') !== 'POST') {
            return true;
        }

        if (!$this->container->request->getPostVar('csrf')) {
            $this->result = [
                'redirect' => !empty($rule['redirect']) ? $rule['redirect'] : null,
                'message'  => !empty($rule['message']) ? $rule['message'] : 'Not allowed!'
            ];
            return false;
        }

        $csrf = $this->container->session->csrf;
        if (($key = in_array($this->container->request->getPostVar('csrf'), $csrf, true)) !== null) {

            unset( $csrf[$key] );

            $this->container->request->setPostVar('csrf', null);
            $this->container->session->csrf = $csrf;

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
        return preg_replace_callback(
            '/(<form[^>]*>)(.*?)(<\/form>)/m',
            array($this, 'insertProtect'),
            $params['data']
        );
    }

    /**
     * Insert CSRF protect into forms
     *
     * @access public
     *
     * @param array $matches Form
     *
     * @return string
     */
    public function insertProtect( array $matches = [] )
    {
        $gen = md5(mt_rand());
        $s = $this->container->session;
        $arr = $s->csrf;
        $arr[] = md5($gen);
        $s->csrf = $arr;
        return $matches[1]."<input type=\"hidden\" name=\"csrf\" value=\"".$gen."\" />".$matches[2].$matches[3];
    }
}
