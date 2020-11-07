@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/p" enctype="multipart/form-data" method="POST">
        {{-- enctype='multipart/form-data is an encoding type that allows files to be sent through a POST. --}}
        @csrf
        {{-- csrf adds a hidden field name _token. laravel uses this token to validate the form
            by checking if this form coming from the same server --}}
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row">
                    <h1>Add New Post</h1>
                </div>
                <div class="form-group row">
                    <label for="caption" class="col-md-4 col-form-label">Post Caption</label>
            
                    <input id="caption" type="caption" class="form-control @error('caption') is-invalid @enderror" name="caption" value="{{ old('caption') }}" required autocomplete="caption">
            
                    @error('caption')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror  
                </div>
                <div class="row">
                    <label for="image" class="col-md-4 col-form-label">Post Image</label>
                    <input type="file" name="image" id="image" class="form-control-file">
                    @error('image')
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror  
                </div>
                <div class="row pt-4">
                    <button type="submit" class="btn btn-primary">Add new post</button>
                </div>
            </div>
        </div> 
    </form>
</div>
@endsection
