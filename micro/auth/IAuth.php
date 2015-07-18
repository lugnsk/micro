<?php /** MicroInterfaceAuth */

namespace Micro\auth;

interface IAuth
{
    /**
     * Check user access to permission
     *
     * @access public
     *
     * @param integer $userId user id
     * @param string $permission checked permission
     * @param array $data for compatible, not used!
     *
     * @return bool
     */
    public function check($userId, $permission, array $data = []);
}