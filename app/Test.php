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
        return $this->result == 'NEGATIVE' ? "text-success" : "text-danger";
    }

    public function getFormattedTestDateAttribute()
    {
        return Carbon::parse($this->test_date)->format('F j, Y');
    }
}
