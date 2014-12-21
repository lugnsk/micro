<?php /** AccessFilterMicro */

namespace Micro\filters;

use Micro\base\Exception;
use Micro\base\Registry;

/**
 * Class AccessFilter
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
class AccessFilter extends Filter
{
    /**
     * PostFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return mixed
     */
    public function post(array $params)
    {
        return $params['data'];
    }
    /**
     * PreFilter
     *
     * @access public
     * @param array $params checked items and other params
     * @return boolean
     * @throws Exception
     */
    public function pre(array $params)
    {
        foreach ($params['rules'] AS $rule) {
            $res = $this->checkRule($rule);

            if ($res===true) {
                return true;
            } elseif ($res===false) {
                throw new Exception( isset($rule['message']) ? $rule['message'] : 'Access denied!' );
            } elseif ($res===NULL) {
                continue;
            }
        }
        return true;
    }

    /**
     * Check one rule
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool|null
     */
    protected function checkRule(array $rule)
    {
        if (
               $this->matchAction($rule)
            AND $this->matchUser($rule)
            AND $this->matchRole($rule)
            AND $this->matchIP($rule)
            AND $this->matchVerb($rule)
        ) {
            return $rule['allow'];
        } else {
            return NULL;
        }
    }
    /**
     * Match action
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchAction($rule) {
        if (!isset($rule['actions']) OR !$rule['actions']) {
            return true;
        }
        if (is_array($rule['actions'])) {
            return in_array($this->action, $rule['actions']);
        } else {
            return $this->action==$rule['actions'];
        }
    }
    /**
     * Match user
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchUser($rule) {
        if (!isset($rule['users']) OR !$rule['users']) {
            return true;
        }
        if (is_array($rule['users'])) {
            foreach ($rule['users'] AS $u) {
                switch ($u) {
                    case '*': { return true; }
                    case '?': { if (Registry::get('user')->isGuest()) return true; break; }
                    case '@': { if (!Registry::get('user')->isGuest()) return true; break; }
                    default:  { if (Registry::get('user')->getID()==$rule['users']) return true; }
                }
            }
        } else {
            switch ($rule['users']) {
                case '*': { return true; }
                case '?': { if (Registry::get('user')->isGuest()) return true; break; }
                case '@': { if (!Registry::get('user')->isGuest()) return true; break; }
                default:  { if (Registry::get('user')->getID()==$rule['users']) return true; }
            }
        }
        return false;
    }
    /**
     * Match role
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchRole($rule) {
        if (!isset($rule['roles']) OR !$rule['roles']) {
            return true;
        }
        if (is_array($rule['roles'])) {
            foreach ($rule['roles'] AS $role) {
                if (Registry::get('user')->check($role)) {
                    return true;
                }
            }
        } else {
            if (Registry::get('user')->check($rule['roles'])) {
                return true;
            }
        }
        return false;
    }
    /**
     * Match IP
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchIP($rule) {
        if (!isset($rule['ips']) OR !$rule['ips']) {
            return true;
        }
        $ip = Registry::get('request')->getUserIP();

        if (is_array($rule['ips'])) {
            foreach ($rule['ips'] AS $r) {
                if ($r==='*' || $r===$ip || (($pos=strpos($r, '*'))!==false && !strncmp($ip,$r,$pos))) {
                    return true;
                }
            }
        } else {
            $r = $rule['ips'];
            if ($r==='*' || $r===$ip || (($pos=strpos($r, '*'))!==false && !strncmp($ip,$r,$pos))) {
                return true;
            }
        }
        return false;
    }
    /**
     * Match verbose
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchVerb($rule){
        if (!isset($rule['verb']) OR !$rule['verb']) {
            return true;
        }
        if (is_array($rule['verb'])) {
            $verb = Registry::get('request')->getMethod();
            foreach ($rule['verb'] AS $v) {
                if ($v==$verb) {
                    return true;
                }
            }
        } else {
            return $rule['verb']==Registry::get('request')->getMethod();
        }
        return false;
    }
}