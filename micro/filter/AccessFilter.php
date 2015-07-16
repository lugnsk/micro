<?php /** AccessFilterMicro */

namespace Micro\filters;

use Micro\base\Exception;

/**
 * Class AccessFilter
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage filter
 * @version 1.0
 * @since 1.0
 */
class AccessFilter extends Filter
{
    /**
     * PostFilter
     *
     * @access public
     *
     * @param array $params checked items and other params
     *
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
     *
     * @param array $params checked items and other params
     *
     * @return boolean
     * @throws Exception
     */
    public function pre(array $params)
    {
        foreach ($params['rules'] AS $rule) {
            $res = $this->checkRule($rule);

            if ($res === true) {
                return true;
            } elseif ($res === false) {
                $this->result = [
                    'redirect' => !empty($rule['redirect']) ? $rule['redirect'] : null,
                    'message' => !empty($rule['message']) ? $rule['message'] : 'Access denied!'
                ];

                return false;
            } elseif ($res === null) {
                continue;
            }
        }

        return true;
    }

    /**
     * Check one rule
     *
     * @access protected
     *
     * @param array $rule rule definition
     *
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
            return null;
        }
    }

    /**
     * Match action
     *
     * @access protected
     *
     * @param array $rule rule definition
     *
     * @return bool
     */
    protected function matchAction($rule)
    {
        if (empty($rule['actions'])) {
            return true;
        }
        if (is_array($rule['actions'])) {
            return in_array($this->action, $rule['actions'], true);
        } else {
            return $this->action === $rule['actions'];
        }
    }

    /**
     * Match user
     *
     * @access protected
     * @global      Container
     *
     * @param array $rule rule definition
     *
     * @return bool
     */
    protected function matchUser($rule)
    {
        if (empty($rule['users'])) {
            return true;
        }
        if (is_array($rule['users'])) {
            foreach ($rule['users'] AS $u) {
                switch ($u) {
                    case '*': {
                        return true;
                    }
                    case '?': {
                        if ($this->container->user->isGuest()) {
                            return true;
                        }
                        break;
                    }
                    case '@': {
                        if (!$this->container->user->isGuest()) {
                            return true;
                        }
                        break;
                    }
                    default: {
                        if ($this->container->user->getID() === $u) {
                            return true;
                        }
                    }
                }
            }
        } else {
            switch ($rule['users']) {
                case '*': {
                    return true;
                }
                case '?': {
                    if ($this->container->user->isGuest()) {
                        return true;
                    }
                    break;
                }
                case '@': {
                    if (!$this->container->user->isGuest()) {
                        return true;
                    }
                    break;
                }
                default: {
                    if ($this->container->user->getID() === $rule['users']) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Match role
     *
     * @access protected
     * @global      Container
     *
     * @param array $rule rule definition
     *
     * @return bool
     */
    protected function matchRole($rule)
    {
        if (empty($rule['roles'])) {
            return true;
        }
        if (is_array($rule['roles'])) {
            foreach ($rule['roles'] AS $role) {
                if ($this->container->user->check($role)) {
                    return true;
                }
            }
        } else {
            if ($this->container->user->check($rule['roles'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match IP
     *
     * @access protected
     * @global      Container
     *
     * @param array $rule rule definition
     *
     * @return bool
     */
    protected function matchIP($rule)
    {
        if (empty($rule['ips'])) {
            return true;
        }
        $ip = $this->container->request->getUserIP();

        if (is_array($rule['ips'])) {
            foreach ($rule['ips'] AS $r) {
                if ($r === '*' || $r === $ip || (($pos = strpos($r, '*')) !== false && !strncmp($ip, $r,
                            $pos))
                ) {
                    return true;
                }
            }
        } else {
            $r = $rule['ips'];
            if ($r === '*' || $r === $ip || (($pos = strpos($r, '*')) !== false && !strncmp($ip, $r, $pos))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match verbose
     *
     * @access protected
     * @global      Container
     *
     * @param array $rule rule definition
     *
     * @return bool
     */
    protected function matchVerb($rule)
    {
        if (empty($rule['verb'])) {
            return true;
        }
        if (is_array($rule['verb'])) {
            $verb = $this->container->request->getMethod();
            foreach ($rule['verb'] AS $v) {
                if ($v === $verb) {
                    return true;
                }
            }
        } else {
            return $rule['verb'] === $this->container->request->getMethod();
        }

        return false;
    }
}