<?php

namespace App\Http\Controllers;

use App\Ev;
use App\Type;
use Image;
use NcJoes\OfficeConverter\OfficeConverter;

use CloudConvert;

use Illuminate\Http\Request;

class EvsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $evs = Type::orderBy('type', 'asc')->with('evs')->withCount('evs')->distinct('type')->get();
        return response()->json($evs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request,[
            'title' =>       'required',
            'image' =>       'required', //
           // 'pdf' =>       'required',
            'type' =>        'required',
          ]);


          $pdf_path = "";
          if ($request->file('pdf') != "") {
          $file = $request->file('pdf');
          $filenametostore = time().'_'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
          $pdf_path= $file->move('storage/pdf',$filenametostore);

          }
        $image_path = "";
        if ($request->file('image') != "") {
        $file = $request->file('image');
        $filenametostore = time().'_'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
        $image_path= $file->move('storage/images',$filenametostore);
        

       // $converter = new OfficeConverter($filenametostore, 'storage/images');
        //CloudConvert::file($image_path)->pageRange(2, 4)->to('jpg');


        $thumbnailpath = public_path('storage/images/'.$filenametostore);
        $img = Image::make($thumbnailpath)->resize(800, 530, function($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($thumbnailpath);

        }

        
        $e = new Ev();
        $e->title = ucfirst($request->title);
        $e->type_id = $request->type;
        $e->image = "/".$image_path;
        $e->pdf = "/".$pdf_path;

        


        $e->save();
        if (request()->expectsJson()) {
            return response()->json([
                'message' => "Your item has been submitted successfully"
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function show(Ev $ev)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function edit(Ev $ev)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ev $ev)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Ev::destroy($id)) {
            return response()->json([
                'message' => "Your item has been submitted successfully"
            ]);
        }
    }
}
