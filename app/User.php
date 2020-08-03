<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use Carbon\Carbon;
use App\Ldap\Scopes\HasIdCard;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, Notifiable, AuthenticatesWithLdap;

    protected $casts = [
        'roles' => 'array'
    ];

    protected $fillable = ['roles'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function getTodayScreening()
    {
        return $this->screenings()->with(['answers' => function($answer) {
            $answer->with(['question']);
        }])->whereDate('created_at', Carbon::today())->first();
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles ? $this->roles : []);
    }
}
