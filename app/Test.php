<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Test extends Model
{
    protected $appends = ['htmlClass'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHtmlClassAttribute()
    {
        if ($this->result == 'NEGATIVE') {
            $class = "text-success";
        } else if ($this->result == 'POSITIVE') {
            $class = "text-danger";
        } else {
            $class = "";
        }

        return $class;
    }

    public function getFormattedTestDateAttribute()
    {
        return Carbon::parse($this->test_date)->format('F j, Y');
    }
}
