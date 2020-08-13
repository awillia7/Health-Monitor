<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Screening extends Model
{
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overrideUser()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getStatusAttribute()
    {
        if ($this->override_user_id || $this->score < $this->fail_score) {
            return 'CLEARED';
        } else {
            return 'NOT CLEARED';
        }
    }
}
