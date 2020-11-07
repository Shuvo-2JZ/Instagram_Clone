<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;

class PostsController extends Controller
{
    public function __construct()
    {
        // this makes sure that every single route in here requires authorization
        // every function after this function,
        // are going to have authenticated user
        $this->middleware('auth');
    }

    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id'); // profiles table's user_id column/attribute

        $posts = Post::whereIn('user_id',$users)->with('user')->latest()->paginate(5);
        // we are loading the user relationship in Post model using with()

        // paginate is the number of records we want to show at a web page 

        return view('posts.index', compact('posts'));

        // The pluck helper method is used to retrieve a list of specific values from a given $array.
        // It is most useful when used against arrays of objects,
        // but will also work with arrays just as well.

            /**$attendees = collect([
        ['name' => 'Tome Heo', 'email' => 'tom@heo.com', 'city' => 'London'],
        ['name' => 'Jhon Deo', 'email' => 'jhon@deo.com', 'city' => 'New York'],
        ['name' => 'Tracey Martin', 'email' => 'tracey@martin.com', 'city' => 'Cape Town'],
        ['name' => 'Angela Sharp', 'email' => 'angela@sharp.com', 'city' => 'Tokyo'],
        ['name' => 'Zayed Bin Masood', 'email' => 'zayad@masood.com', 'city' => 'Dubai'],]);
        Now we want to extract only the name of the attendees. You can do something like below.

        $names = $attendees->pluck('name')
        // ['Tome Heo', 'Jhon Deo', 'Tracey Martin', 'Angela Sharp', 'Zayed Bin Masood']; */
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        $data = request()->validate(
            [
                'caption' => 'required',
                'image' => 'required|image' // ['required', 'image']
            ]
        );

        $imagePath = request('image')->store('uploads', 'public'); // $imagePath returns 'uploads/ajnjnfdplgf.jpeg'
        // the image is going to be in storage/app/public/uploads directory
        // public is the storage type i.e local, amazon s3 etc...

        // Post::create($data);
        // this will note work
        // we did not provide 'user_id' to the table 'posts'
        // we have to provide 'user_id' through relationship

        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200); // single quotation marks ('') will not work
        // the public_path is accessing the folder public/storage/uploads/the_image.jpeg
        $image->save();

        auth()->user()->posts()->create(
            [
                // $data is an array with elements 'caption' and 'image'
                // we are setting the Post table's attributes(column): 'caption' and 'image'
                // to the values of $data array
                'caption' => $data['caption'],
                'image' => $imagePath
            ]
        );

        // we are applying create method on posts() method
        // posts() method returns Post class
        // so we are applying create method on Post class
        // laravel will provide the foreign key 'user_id' to the post table
        // through this relationship

        return redirect('/profile/'.auth()->user()->id);
    }

    public function show(Post $post)
    {
        // $post is the authenticated user's id

        return view('posts.show', compact('post'));
    }
}
