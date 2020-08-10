<?php namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;   
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller {

    public function all(){
        return response()->json(Todo::orderBy('id', 'desc')->get());
    }
}