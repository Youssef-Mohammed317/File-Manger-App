<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class File extends Model
{
    use NodeTrait, SoftDeletes;

    protected $guarded = [];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
