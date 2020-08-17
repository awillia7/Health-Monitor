<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use LdapRecord\Models\ActiveDirectory\User AS LdapUser;

class ScreeningByErpController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = User::where('erp_id', str_pad($request->user, 7, '0', STR_PAD_LEFT))->first();
        if (!$user) {
            // Search for user in AD
            $ldapUser = LdapUser::where('employeeNumber', '=', str_pad($request->user, 7, '0', STR_PAD_LEFT))->first();
            
            if ($ldapUser && ($ldapUser->getFirstAttribute('samAccountName') === getenv("ADMIN_ACCOUNT") 
            || !empty($ldapUser->getFirstAttribute('extensionAttribute13')))) {
                // Add new user found in LDAP
                $user = new User();
                $user->name = $ldapUser->getFirstAttribute('displayName');
                $user->first_name = $ldapUser->getFirstAttribute('givenName');
                $user->last_name = $ldapUser->getFirstAttribute('sn'); 
                $user->username = $ldapUser->getFirstAttribute('samAccountName');
                $user->email = $ldapUser->getFirstAttribute('mail');
                $user->erp_id = $ldapUser->getFirstAttribute('employeeNumber');
                $user->infinias_id = $ldapUser->getFirstAttribute('extensionAttribute13');
                $user->guid = $ldapUser->getConvertedGuid();
                $user->domain = 'default';
                $user->save();
            }
        }

        if (!$user) {
            return response()->json(null, 404);
        }

        $screening = User::where('erp_id', str_pad($request
            ->user, 7, '0', STR_PAD_LEFT))
            ->first()->getTodayScreening();
        
        $status = null;
        if (!$screening) {
            // Default CLEARED for employee users (email domain not @mail.mvnu.edu)
            if (preg_match("/@mail\.mvnu\.edu/i", $user->email)) {
                $status = null;
            } else {
                $status = 'CLEARED';
            }
        } else {
            $status = $screening->status;
        }

        return response()->json([
            'id' => $request->user,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'status' => $status
        ]);
    }
}
