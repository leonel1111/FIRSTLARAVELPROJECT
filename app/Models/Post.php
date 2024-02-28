<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // in simple terms, this code tells Laravel that
    //  when you create or update a record in a database
    //   using this model, you are allowed to set the "title," "body," and "user_id" 
    //   fields all at once. It's like saying, "Hey Laravel, it's okay to set these 
    //   specific pieces of information in a single step." This is a security measure 
    //   to make sure that only the fields you specify can be easily modified, and it
    //    helps protect your application from unwanted changes to other fields in the database.
    protected $fillable = [
        "title", 'body', 'user_id'
    ];

    public function person() {
        //connects the two tables by the user_id
        return $this->belongsTo(User::class, 'user_id');
    }
}
