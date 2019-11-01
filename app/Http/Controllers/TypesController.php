<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;

class TypesController extends Controller
{

    public function create()
    {
      return  view('type');  
    }
    public function store(Request $request){
      
        $t = new Type();
        $t->type = $request->type;

        $t->save();
        if (request()->expectsJson()) {
            return response()->json([
                'message' => "Your item has been submitted successfully"
            ]);
        }


    }
}
