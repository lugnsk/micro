<?php /** MicroRoleBasedAccessControl */

namespace Micro\auth;

interface Rbac {
    const TYPE_ROLE       = 0;
    const TYPE_PERMISSION = 1;
    const TYPE_OPERATION  = 2;


    public function create($name, $type = self::TYPE_ROLE, $based=null, $data=null);
    public function delete($name);

    public function assign($userId, $name);
    public function revoke($userId, $name);

    public function tree(&$elements, $parentId=0);
    public function assigned($userId);

    public function check($userId, $action, $data=null);
}