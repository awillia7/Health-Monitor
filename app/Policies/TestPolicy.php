<?php

namespace App\Policies;

use App\Test;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;


class TestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        return in_array("TESTS_VIEW", $user->roles) || in_array("TESTS_RESULTS", $user->roles);
    }

    public function create(User $user)
    {
        return in_array("TESTS_IMPORT", $user->roles);
    }
}
