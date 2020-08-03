<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array("ADMIN", $user->roles);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return in_array("ADMIN", $user->roles);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array("ADMIN", $user->roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return in_array("ADMIN", $user->roles);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
