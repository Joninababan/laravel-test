<?php

namespace App\Http\Controllers;

use App\BookReview;
use App\Book;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {   
        
        // @TODO implement
        $validator = Validator::make($request->all(), [
            'review'   => 'required|max:10|integer',
            'comment'  => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        
        $bookReview = new BookReview();
        $bookReview->review = $request->review;
        $bookReview->comment = $request->comment;

        $book = Book::find($bookId);
        $bookReview->book_id = $bookId;
        $bookReview->user_id = Auth::id();
        

        $bookReview->save();

        if(!$book){
            return response()->json([
            ], 404);
        }

    
        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement

        $book = Book::find($bookId);
        if(!$book){
            return response()->json([
            ], 404);
        }
        $bookreview = BookReview::find($reviewId);

        if($bookreview && $book) {
            $bookreview->delete();
            $book->delete();
            return response()->json([
            ], 204);

        }

        return response()->json([
        ], 404);
    }
}
