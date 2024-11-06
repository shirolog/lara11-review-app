<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        
        $keyword = request()->input('keyword');


        if(!empty($keyword)){

            $books = Book::where('title', 'like', '%' .$keyword. '%')
            ->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->latest()
            ->paginate(10);

        }else{

            $books = Book::orderBy('created_at', 'DESC')
            ->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->paginate(10);
        }

        return view('books.list', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [

            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];

        if(!empty($request->file('image'))){

            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){

            return redirect()->route('book.create')->withInput()->withErrors($validator);
        }

        $book = new Book;

        $book-> title = $request->input('title');
        $book-> author = $request->input('author');
        $book-> description = $request->input('description');
        $book-> status = $request->input('status');
        $book->save();

        if(!empty($request->file('image'))){

            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books/'), $imageName);
            $book->image = $imageName;
            $book->save();

        }


        return redirect()->route('book.index')->with('success', 'Book added successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        
        $rules = [

            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];

        if(!empty($request->file('image'))){

            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){

            return redirect()->route('book.edit', $book->id)->withInput()->withErrors($validator);
        }

        $book-> title = $request->input('title');
        $book-> author = $request->input('author');
        $book-> description = $request->input('description');
        $book-> status = $request->input('status');
        $book->save();


        if(!empty($request->file('image'))){


            $oldImage = public_path('uploads/books/'. $book->image);
            
            if(is_file($oldImage)){
                unlink($oldImage);
            }


            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books/'), $imageName);
            $book->image = $imageName;
            $book->save();

        }


        return redirect()->route('book.edit', $book->id)->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {   


        $book->delete();

        $oldImage = public_path('uploads/books/'. $book->image);
        
        if(is_file($oldImage)){
            unlink($oldImage);
        }

        session()->flash('success', 'Book deleted successfully!');

        return response()->json([
            'status' => true,
            'message' => 'Book deleted successfully!',
        ]);

        
    }
}
