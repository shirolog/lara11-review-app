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
                        Books
                    </div>

                    <div class="card-body pb-0">
                                    
                        <div class="d-flex justify-content-between">
                            <a href="{{route('book.create')}}" class="btn btn-primary">Add Book</a>            
                            <form action="" method="get">
                                @csrf
                                <div class="d-flex">
                                    <input type="text" name="keyword" class="form-control" value="{{Request::get('keyword')}}" placeholder="Keyword">
                                    <button type="submit"  class="btn btn-primary ms-2">Search</button>
                                    <a href="{{route('book.index')}}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form>
                        </div>

                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                                <tbody>
                                    @if($books->isNotEmpty())
                                        @foreach($books as $book)
                                        @php 
                                        if($book->reviews_count > 0){

                                            $aveRating = $book->reviews_sum_rating / $book->reviews_count;
                                        }else{
                                            $aveRating = 0;
                                        }
                                        $avgRatingPer = ($aveRating*100)/5;
                                    @endphp
                                        <tr>
                                            <td>{{$book->title}}</td>
                                            <td>{{$book->author}}</td>
                                            <td>{{number_format($aveRating, 1)}} (<span class="theme-font text-muted">{{($book->reviews_count) ? $book->reviews_count. ' Reviews ' : $book->reviews_count. ' Review '}}</span>
                                            )

                                            </td>

                                            <td>
                                                @if($book->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                                <a href="{{route('book.edit', $book->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-button" data-id="{{$book->id}}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>

                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">Result not found</td>
                                            </tr>
                                    @endif
                                </tbody>
                            </thead>
                        </table> 
                        @if($books->isNotEmpty())
                            {{$books->links()}}  
                        @endif
                    </div>
                    
                </div>                
            </div>
               
        </div>
    </div>       
@endsection

@section('script')

<script>
  // 削除ボタンがクリックされたときの処理
  $(document).on('click', '.delete-button', function(e) {
      e.preventDefault();
      const bookId = $(this).data('id'); 

      if (confirm('Are you sure you want to delete?')) {
          deleteBook(bookId);
      }
  });

  // 本の削除リクエストを送信
  function deleteBook(bookId) {
      $.ajax({
          url: '{{route("book.destroy", ":id")}}'.replace(':id', bookId),
          type: 'DELETE',
          headers: {
              'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          success: function(response) {
              if (response.status) {
                  window.location.href = '{{route("book.index")}}';
              }
          },
          error: function() {
              alert('Failed to delete the book.');
          }
      });
  }
</script>


@endsection