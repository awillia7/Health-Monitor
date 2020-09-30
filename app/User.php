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

    protected $appends = ['type', 'formatted_test_optin_date', 'formatted_test_print_date'];

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

    public function overrideScreenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function getTodayScreening()
    {
        return $this->screenings()->with(['answers' => function($answer) {
            $answer->with(['question']);
        }, 'user'])->whereDate('created_at', Carbon::today())->first();
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles ? $this->roles : []);
    }

    public function getTypeAttribute()
    {
        if (preg_match("/\@mvnu\.edu$/s", $this->email)) {
            return "EMPLOYEE";
        } elseif (preg_match("/\@mail\.mvnu\.edu$/s", $this->email)) {
            return "STUDENT";
        }
        
        return null;
    }

    public function getFormattedTestOptinDateAttribute()
    {
        if ($this->test_optin_date) {
            $date = Carbon::parse($this->test_optin_date);
        
            return $date->toFormattedDateString();
        }

        return null;
    }

    public function getFormattedTestPrintDateAttribute()
    {
        if ($this->test_print_date) {
            $date = Carbon::parse($this->test_print_date);

            return $date->toFormattedDateString();
        }

        return null;
    }
}
