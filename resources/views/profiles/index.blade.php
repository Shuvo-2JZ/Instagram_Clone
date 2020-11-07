@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-4 pl-5">
                <img src="{{$user->profile->profileImage()}}" class="img-responsive rounded-circle" style="max-width: 70%">
            </div>
            <div class="col-7">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div class="d-flex align-items-center pb-3">
                        <div class="h4">{{$user->username}}</div>
                        <follow-button user-id="{{$user->id}}" follows="{{$follows}}"></follow-button>
                    </div>
                    @can('update', $user->profile)
                        <a href="/p/create">Add new post</a>
                        {{-- we could see this link without logged in, now we cannot --}}
                    @endcan 
                </div>
                @can('update', $user->profile)
                    <a href="/profile/{{$user->id}}/edit">Edit Profile</a>
                    {{-- we could see this link without logged in, now we cannot --}}
                @endcan
                <div class="d-flex pt-3">
                    <div class="pr-5"><strong>{{$postCount}} </strong>posts</div>
                    <div class="pr-5"><strong>{{$followersCount}} </strong>followers</div>
                    <div class="pr-5"><strong>{{$followingCount}} </strong>following</div>
                </div>
                <div class="pt-4 font-weight-bold">{{$user->profile->title}}</div>
                <div>{{$user->profile->description}}</div>
                <div><a href="{{$user->profile->url}}">{{$user->profile->url}}</a></div>
            </div>
        </div>
        <div class="row pt-5">
            @foreach ($user->posts as $post)
            <div class="col-4 pb-5">
                <a href="/p/{{$post->id}}">
                    <img src="/storage/{{$post->image}}" class="w-100"> 
                </a>
            </div>
        @endforeach
        </div>
    </div>
@endsection