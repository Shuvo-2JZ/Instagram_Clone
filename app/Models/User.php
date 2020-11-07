<?php

namespace App\Models;

use App\Mail\NewUserWelcomeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded =[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // when we log in, we are creating a user and a profile
    // when we create a user, we want to show the user an empty profile
    // in other words, we want to show the user the index.blade.php file in profiles folder
    // but, index.blade.php file needs title, description, url from the profile table
    // but the profile table is not being created, when we create/register a new user
    // so, the index.blade.php file will return an error for not having title, description, url
    // of the profile table
    // this is why when we create/register a new user, we need to instantiate 2 models, User and Profile. 

    // The static boot() method is automatically run whenever a model is instantiated
    protected static function boot()
    {
        parent::boot();

        static::created(function ($gg){
            // the $gg is an instance of the User class/model
            // the $gg can be any name i.e $user
            $gg->profile()->create([
                // creating the profile database
                'title'=>$gg->username 
            ]);

            Mail::to($gg->email)->send(new NewUserWelcomeMail());
        });
    }

    // a User has one Profile
    // one to one relationship
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // a user has many posts
    // one to many relationship
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    // a user can follow many profiles
    // a profile can have many followers
    // many to many relationship
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }
}