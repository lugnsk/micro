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
     * Check one rule
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool|null
     */
    protected function checkRule(array $rule)
    {
        if ($this->matchAction($rule)
            && $this->matchRole($rule)
            && $this->matchUser($rule)
            && $this->matchIP($rule)
            && $this->matchVerb($rule)
        ) {
            return (array_shift($rule)==='allow') ? true : false;
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
        if (isset($rule['actions']) and $rule['actions']) {
            if (is_array($rule['actions'])) {
                return in_array($this->action, $rule['actions']);
            } else {
                return $rule['actions']==$this->action;
            }
        }
        return true;
    }

    /**
     * Match role
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchRole($rule) {
        if (isset($rule['roles']) AND $rule['roles']) {
            if (is_array($rule['role'])) {
                foreach ($rule['role'] AS $role) {
                    if (!Registry::get('user')->check($role)) {
                        return false;
                    }
                }
                return true;
            } else {
                return Registry::get('user')->check($rule['role']);
            }
        }
        return true;
    }

    /**
     * Match user
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchUser($rule) {
        $user = Registry::get('user');

        if (isset($rule['users']) AND $rule['users']) {
            if (is_array($rule['users'])) {
                foreach ($rule['users'] AS $u) {
                    switch ($u) {
                        case '*': { return true;                           }
                        case '?': { return $user->isGuest();               }
                        case '@': { return !$user->isGuest();              }
                        default:  { return $user->getID()==$rule['users']; }
                    }
                }
            } else {
                switch ($rule['users']) {
                    case '*': { return true;                           }
                    case '?': { return $user->isGuest();               }
                    case '@': { return !$user->isGuest();              }
                    default:  { return $user->getID()==$rule['users']; }
                }
            }
        }
        return true;
    }

    /**
     * Match IP
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchIP($rule) {
        $ip = Registry::get('request')->getUserIP();

        if (isset($rule['ips']) AND $rule['ips']) {
            if (is_array($rule['ips'])) {
                foreach ($rule['ips'] AS $r) {
                    if (!($r==='*' || $r===$ip || (($pos=strpos($r, '*'))!==false && !strncmp($ip,$r,$pos)))) {
                        return false;
                    }
                }
            } else {
                $r = $rule['ips'];
                return $r==='*' || $r===$ip || (($pos=strpos($r, '*'))!==false && !strncmp($ip,$r,$pos));
            }
        }
        return true;
    }

    /**
     * Match verbose
     *
     * @access protected
     * @param array $rule rule definition
     * @return bool
     */
    protected function matchVerb($rule){
        $verb = Registry::get('request')->getMethod();
        if (isset($rule['verb']) AND $rule['verb']) {
            if (is_array($rule['verb'])) {
                foreach ($rule['verb'] AS $v) {
                    if (!($v==$verb)) {
                        return false;
                    }
                }
            } else {
                return $rule['verb']==$verb;
            }
        }
        return true;
    }
}