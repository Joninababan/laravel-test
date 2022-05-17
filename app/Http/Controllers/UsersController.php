<?php

namespace App\Http\Controllers;

use App\User;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;



class UsersController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        $user = User::latest()->get();
       

        // return 'test';
        return [
            // @TODO implement
            'data' => $user
        ];
    }

    

   
}
