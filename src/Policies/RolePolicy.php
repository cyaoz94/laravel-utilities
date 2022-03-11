<?php

namespace Cyaoz94\LaravelUtilities\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;
use Cyaoz94\LaravelUtilities\Models\AdminUser;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the role.
     *
     * @param AdminUser $user
     * @param Role $role
     * @return mixed
     */
    public function update(AdminUser $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be updated.')
            : true;
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param AdminUser $user
     * @param Role $role
     * @return mixed
     */
    public function delete(AdminUser $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be deleted.')
            : true;
    }

    public function assignRole(AdminUser $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be created.')
            : true;
    }
}
