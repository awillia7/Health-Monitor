<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Screening;
use LdapRecord\Models\ActiveDirectory\User AS LdapUser;

class ScreeningSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:MANAGER');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $search = null;
        if (preg_match("/^\d+$/", $request->screeningSearch)) {
            // Search by ID
            $search = str_pad($request->screeningSearch, 7, "0", STR_PAD_LEFT);
            $user = User::where('erp_id', $search)->first();
        } else {
            // Search by username
            $search = $request->screeningSearch;
            $user = User::where('username', $search)->first();
        }

        if ($user) {
            $screening = $user->getTodayScreening();

            if ($request->expectsJson()) {
                $id = $screening ? $screening->id : null;
                $score = $id ? $screening->score : null;
                $fail_score = $id ? $screening->fail_score : null;
                $override_at = $id ? $screening->override_at : null;
                $override_user_id = $id ? $screening->override_user_id : null;
                
                return response()->json([
                    "id" => $id,
                    "score" => $score,
                    "fail_score" => $fail_score,
                    "override_at" => $override_at,
                    "override_user_id" => $override_user_id,
                    'user' => $user
                ]);
            }

            if ($screening) {
                return redirect()->route('screenings.show' ,['screening' => $screening]);
            } else {
                return redirect()->route('screenings.index');
            }
        }

        // Search AD for valid user
        if (preg_match("/^\d+$/", $request->screeningSearch)) {
            // Search by ID
            $user = LdapUser::where('employeeNumber', '=', $search)->first();
        } else {
            // Search by username
            $user = LdapUser::where('sAMAccountName', '=', $search)->first();
        }

        if ($user && ($user->getFirstAttribute('samAccountName') === getenv("ADMIN_ACCOUNT") 
            || !empty($user->getFirstAttribute('extensionAttribute13')))) {
            
            // Add new user found in LDAP
            $newUser = new User();
            $newUser->name = $user->getFirstAttribute('displayName');
            $newUser->first_name = $user->getFirstAttribute('givenName');
            $newUser->last_name = $user->getFirstAttribute('sn'); 
            $newUser->username = $user->getFirstAttribute('samAccountName');
            $newUser->email = $user->getFirstAttribute('mail');
            $newUser->erp_id = $user->getFirstAttribute('employeeNumber');
            $newUser->infinias_id = $user->getFirstAttribute('extensionAttribute13');
            $newUser->guid = $user->getConvertedGuid();
            $newUser->domain = 'default';
            $newUser->save();

            // Return new user
            if ($request->expectsJson()) {
                return response()->json([
                    "id" => null,
                    "score" => null,
                    "fail_score" => null,
                    "override_at" => null,
                    "override_user_id" => null,
                    'user' => $newUser
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "User not found"
            ], 204);
        }

        return redirect()->route('screenings.index');
    }
}
