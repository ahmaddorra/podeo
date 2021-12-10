@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <form action="{{ route('creator.podcasts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{old('title')}}">
                @error('title')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control @error('description') is-invalid @enderror" placeholder="Leave a description here" name="description"
                              id="description" style="height: 100px">{{old('description')}}</textarea>
                    <label for="description">Description</label>
                    @error('description')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select @error('category') is-invalid @enderror">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" @if(old('category') == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image">
                @error('image')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary float-end">Submit</button>
        </form>
    </div>
@endsection
