<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        // we have to fill the Profile table's user_id with correct data
        // otherwilse the relationship will not work

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        // if the user is authenticated,
        // then is the authenticated user's following contains the $user
        // following returns a collection of Profile class
        // profile table has foreign key user_id
        // contains method matches User table's id with the profile tables user_id

        // The contains() method determines if a given key exist in the collection. 
        // You can also provide the value to check against the key as a second argument,
        // which will check if the given key/value pair exists in the collection.
        // it is a boolean type

        // $postCount = Cache::remember('count.posts.'.$user->id, now()->addSeconds(30), function () use($user) 
        // {
        //     return $user->posts->count();   
        // });

        // $followersCount = Cache::remember('count.followers.'.$user->id, now()->addSeconds(30), function () use($user) 
        // {
        //     return $user->profile->followers->count();   
        // });

        // $followingCount = Cache::remember('count.following.'.$user->id, now()->addSeconds(30), function () use($user) 
        // {
        //     return $user->following->count();
        // });

        
        $postCount = $user->posts->count();
        $followersCount = $user->profile->followers->count();
        $followingCount = $user->following->count();

        
        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        //$p = new Profile();
        //$this->authorize('update', $p);

        /**
         * this method accepts the name of the action
         * you wish to authorize and the relevant model
         */
        // we ar authorizing the Profile model
        // so that a user who is not authorized/logged in
        // can not see/change this action
        // in this case, the relevant model is the Profile
        
        // update is action name
        // $p is the relevant model object

        $this->authorize('update',$user->profile);
        // we are using the relation $user->profile
        // because we are getting the user id/object from the request
        // we don't need to create a Profile object and use it 

        return view('profiles.edit', compact('user'));

        // The current user can update this block of code/edit function
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate(
            [
                'title' => 'required',
                'description' => 'required',
                'url' => 'url',
                'image' => ''
            ]
        );

        // the user might keep their old profile picture
        // so we need to check if the user uploaded new picture or not
        if (request('image'))
        {
            $imagePath = request('image')->store('profile', 'public'); // $imagePath returns 'profile/ajnjnfdplgf.jpeg'
            // the image is going to be in storage/app/public/profile directory
            // public is the storage type i.e local, amazon s3 etc

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000); // single quotation marks ('') will not work
            // the public_path is accessing the folder public/storage/profile/the_image.jpeg
            $image->save();

            // $user()->profile->update($data);
            // but, in this way we can edit profile without logged in
            auth()->user()->profile->update(array_merge(
                $data,
                ['image' => $imagePath]
            ));
            // merging arrays
            // we are replacing the 'image' in $data array with $imagePath
        }

        else
        {
            auth()->user()->profile->update($data);
        }

        return redirect('/profile/'.$user->id);
    }
}