<?php

namespace App\Http\Controllers;

use App\Book;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;



class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        $page = $request->page;
        $sort = $request->sortby;
        $direction = $request->direction;
        $title = $request->title;
        $authors = $request->authors;
        $integerIDs = array_map('intval', explode(',', $authors));

        $myArr = [];
        foreach ($integerIDs AS $x){
            array_push($myArr, $x);
        }

        if ($sort and $page and $direction and $title and $authors){
            $books = Book::whereHas('authors', function (Builder $query) use($myArr) {
                $query->whereIn('id', $myArr);
            })->where('title', 'LIKE', "%{$title}%")->orderBy($sort, $direction)->paginate($page);;
        }
        else{
            $books = Book::latest()->paginate($page);
        }
       

        return BookResource::collection($books);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $validator = Validator::make($request->all(), [
            'isbn'   => 'numeric|digits:13|required|unique:books',
            'title'  => 'required',
            'description'  => 'required',
            'authors' => 'required',
            'published_year' => 'required|integer|min:1900|max:2020',

        ]);
        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = new Book();
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->title = $request->title;
        $book->published_year = $request->published_year;
        $author = Author::find($request->authors);
        if(!$author){
            return 'ID Author does not exist';
        }
        $book->save();
        
        $book->authors()->attach($author);

        return new BookResource($book);
    }

}
