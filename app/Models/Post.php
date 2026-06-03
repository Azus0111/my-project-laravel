<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "title",
        "slug",
        "content",
        "user_id",
        "image",
        "status",
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
