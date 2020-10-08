<?php

namespace App\Policies;

use App\Screening;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class ScreeningPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any screenings.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the screening.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\Screening  $screening
     * @return mixed
     */
    public function view(User $user, Screening $screening)
    {
        //
    }

    public function index(User $user)
    {
        return in_array("SCREENINGS_VIEW", $user->roles);
    }

    /**
     * Determine whether the user can create screenings.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the screening.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\Screening  $screening
     * @return mixed
     */
    public function update(User $user, Screening $screening)
    {
        //
    }

    /**
     * Determine whether the user can delete the screening.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\Screening  $screening
     * @return mixed
     */
    public function delete(User $user, Screening $screening)
    {
        return in_array("SCREENINGS_DELETE", $user->roles);
    }

    /**
     * Determine whether the user can restore the screening.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\Screening  $screening
     * @return mixed
     */
    public function restore(User $user, Screening $screening)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the screening.
     *
     * @param  \LdapRecord\Models\ActiveDirectory\User  $user
     * @param  \App\Screening  $screening
     * @return mixed
     */
    public function forceDelete(User $user, Screening $screening)
    {
        //
    }
}
