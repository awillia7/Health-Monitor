<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
