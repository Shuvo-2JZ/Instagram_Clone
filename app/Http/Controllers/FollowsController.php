<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // we need to create a pivot table for many to many relationship
    public function store(User $user)
    {
        return auth()->user()->following()->toggle($user->profile);
    }
}