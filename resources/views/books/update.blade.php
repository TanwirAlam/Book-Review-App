@extends('layout.app')

@section('title')
    {{'Update Books'}}
@endsection

@section('content')

<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layout.sidebar')
        </div> 
        <div class="col-md-9">
            @include('layout.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Add Book
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('books.update',$book->id)}}" enctype="multipart/form-data">
                        @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" value="{{old('title',$book->title)}}" class="form-control @error('title') is-invalid @enderror " placeholder="Title" name="title" id="title" />
                        @error('title') <p class="invalid-feedback">{{$message}}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" value="{{old('author',$book->author)}}" class="form-control @error('author') is-invalid @enderror " placeholder="Author"  name="author" id="author"/>
                        @error('author') <p class="invalid-feedback">{{$message}}</p> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror " placeholder="Description" cols="30" rows="5">{{old('description',$book->description)}}</textarea>
                        @error('description') <p class="invalid-feedback">{{$message}}</p> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="Image" class="form-label">Image</label>
                        <input type="file" class="form-control  @error('description') is-invalid @enderror"  name="book_image" id="book_image"/>
                        @if ($book->image)
                        <img src="{{asset('storage/books/thumb/'.$book->image)}}" class="img-fluid mt-4" alt="Luna John" >
                        @endif
                        @error('book_image') <p class="invalid-feedback">{{$message}}</p> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label @error('status') is-invalid @enderror">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{$book->status==1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{$book->status==0 ? 'selected' : ''}}>Block</option>
                        </select>
                        @error('status') <p class="invalid-feedback">{{$message}}</p> @enderror
                    </div>
                    <button class="btn btn-primary mt-2" type="submit">Create</button>  
                </form>                   
                </div>
            </div>                
        </div>
    </div>       
</div>
    
@endsection

