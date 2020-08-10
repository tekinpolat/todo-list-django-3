<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller {
    
    public function index(){
        return view('todo.index');
    }

    public function add(Request $request){

        $todo = new Todo();
        $todo->todo = $request->input('todo');
        $result = $todo->save();
        return response()->json(['result'=> $result]);
    }

    public function all(){
        return response()->json(Todo::orderBy('id', 'desc')->get());
    }

    public function delete(Request $request){
        $id = $request->input('id');

        $todo = Todo::find($id);
        $result = $todo->delete();
        return response()->json(['result'=> $result]);
    }

    public function statusChange(Request $request){
        $id     = $request->input('id');
        $status = $request->input('status');

        $todo   = Todo::find($id);
        $todo->status = $status;
        $result = $todo->save();

        return response()->json(['result'=> $result]);
    }

}