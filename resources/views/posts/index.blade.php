@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($posts as $post)
    <div class="row">
        <div class="col-6 offset-3">
            <a href="/profile/{{$post->user->id}}">
                <img src="/storage/{{$post->image}}" class="w-100">
            </a>
        </div>
    </div>
    <div class="row pb-4 pt-2">
        <div class="col-6 offset-3">
            <div>
                <p>
                    <span class="font-weight-bold">
                        <a class="text-dark" href="/profile/{{$post->user->id}}">
                            {{$post->user->username}} 
                        </a>
                    </span>{{$post->caption}}
                </p>
            </div>
        </div>
    </div>
    @endforeach
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            {{-- after we add the paginate() function --}}
            {{$posts->links()}}
        </div>
    </div>
</div>
@endsection
