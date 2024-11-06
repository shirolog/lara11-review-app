<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    public function index()
    {
        $keyword = request()->input('keyword');
        
        //Book に紐づくreviewsの件数をカウントして、rating カラムの合計値を計算
        $query = Book::withCount('reviews')->withSum('reviews', 'rating')
        ->where('status', 1)->latest();
    
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
    
        $books = $query->paginate(8);
        
        // dd($books);

        return view('home', compact('books'));
    }
    



    public function detail(Book $book){

        if($book->status == 0){
            abort(404);
        }

        $query = Book::withCount('reviews')->withSum('reviews', 'rating')
        ->where('status', 1)->latest();

        $book = $query->find($book->id);

        $relatedBooks = Book::where('status', 1)
        ->withCount('reviews')
        ->withSum('reviews', 'rating')
        ->take(3)
        ->where('id', '!=', $book->id)
        ->inRandomOrder()
        ->get();

       
        return view('book-detail', compact('book', 'relatedBooks'));
    }


    public function saveReview(Request $request){

        $validator = Validator::make($request->all(), [

            'review' => 'required|min:10',
            'rating' => 'required',
        ]);

        if($validator->fails()){

            return response()->json([

                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $countReview = Review::where('user_id', Auth::user()->id)
        ->where('book_id', $request->input('book_id'))
        ->count();

        if($countReview > 0){

            session()->flash('error', 'You already submitted a review!');

            return response()->json([

                'status' => true,
            ]);
        }

        $review = new Review;

        $review->review = $request->input('review');
        $review->rating = $request->input('rating');
        $review->user_id = Auth::user()->id;
        $review->book_id = $request->input('book_id');
        $review->save();

        session()->flash('success', 'Review submitted successfully!');
            
        return response()->json([

            'status' => true,
        ]);

    }

}
