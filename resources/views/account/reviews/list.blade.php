@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')              
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Reviews
                </div>
                <div class="card-body pb-0">            
                        <div class="d-flex justify-content-end">
                            <form action="" method="get">
                                @csrf
                                <div class="d-flex">
                                    <input type="text" name="keyword" class="form-control" value="{{Request::get('keyword')}}" placeholder="Keyword">
                                    <button type="submit"  class="btn btn-primary ms-2">Search</button>
                                    <a href="{{route('review.index')}}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    <table class="table  table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Review</th>
                                <th>Book</th>
                                <th>Rating</th>
                                <th>Created At</th>
                                <th>Status</th>                                  
                                <th width="100">Action</th>
                            </tr>
                            <tbody>
                                @if($reviews->isNotEmpty())
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{$review->review}} <br><strong>{{$review->user->name}}</strong></td>                                        
                                            <td>{{$review->book->title}}</td>
                                            <td>{{$review->rating}}</td>
                                            <td>
                                                {{\Carbon\carbon::parse($review->created_at)->format('d M, Y')}}
                                            </td>
                                            <td>
                                                @if($review->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('review.edit', $review->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:avoid(0);" class="btn btn-danger btn-sm delete-btn" data-id="{{$review->id}}"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">Result not found</td>
                                        </tr>
                                @endif                            
                            </tbody>
                        </thead>
                    </table>   
                    {{$reviews->links()}}                  
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection

@section('script')
<script>
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();

        const reviewId = $(this).data('id');

        if (confirm('Are you sure you want to delete?')) {
            deleteReview(reviewId);
        }
    });

    function deleteReview(reviewId) {
        $.ajax({
            url: '{{route("review.destroy", ":id")}}'.replace(':id', reviewId),
            type: 'DELETE',
            headers:{
            'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            success: function(response) {
                if (response.status) {
                    window.location.href = '{{route("review.index")}}';
                }
            },
        });
    }
</script>

@endsection