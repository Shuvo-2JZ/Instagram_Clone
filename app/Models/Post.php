<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /* A mass-assignment vulnerability occurs when a user passes an unexpected HTTP parameter through a request, and that parameter changes a column in your database you did not expect
     */
    protected $guarded = [];

    // many posts has one user
    // one to many inverse relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
