<?php /** AccessFilterMicro */

namespace Micro\filter;

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
     * @inheritdoc
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
            && $this->matchUser($rule)
            && $this->matchRole($rule)
            && $this->matchIP($rule)
            && $this->matchVerb($rule)
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
        }

        return $this->action === $rule['actions'];
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

        if (!is_array($rule['users'])) {
            $rule['users'][] = $rule['users'];
        }

        foreach ($rule['users'] AS $u) {
            switch ($u) {
                case '*':
                    return true;

                case '?':
                    if ($this->container->user->isGuest()) {
                        return true;
                    }
                    break;

                case '@':
                    if (!$this->container->user->isGuest()) {
                        return true;
                    }
                    break;

                default:
                    if ($this->container->user->getID() === $u) {
                        return true;
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

        if (!is_array($rule['roles'])) {
            $rule['roles'][] = $rule['roles'];
        }

        foreach ($rule['roles'] AS $role) {
            if ($this->container->user->check($role)) {
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

        if (!is_array($rule['ips'])) {
            $rule['ips'][] = $rule['ips'];
        }

        $userIp = $this->container->request->getUserIP();

        foreach ($rule['ips'] AS $r) {
            if ($r === '*' || $r === $userIp || (($pos = strpos($r, '*')) !== false && !strncmp($userIp, $r, $pos))) {
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

        if (!is_array($rule['verb'])) {
            $rule['verb'][] = $rule['verb'];
        }

        $verb = $this->container->request->getMethod();
        foreach ($rule['verb'] AS $v) {
            if ($v === $verb) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function post(array $params)
    {
        return $params['data'];
    }
}
