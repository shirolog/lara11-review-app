@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')              
        </div>
        <div class="col-md-9">
         
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Edit Reviews
                </div>
                <div class="card-body pb-3">            

                    <form action="{{route('review.update', $review->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea name="review" class="form-control @error('review') is-invalid @enderror" id="review" placeholder="Review">{{old('review', $review->review)}}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                                    <option value="1" {{($review->status == 1) ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{($review->status == 0) ? 'selected' : ''}}>Block</option>
                                </select>
                            @error('status')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
              
                        <button class="btn btn-primary mt-2">Update</button>                     
                    </form>
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection