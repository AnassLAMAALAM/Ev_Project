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
    
    public function evs()
    {

        $evs = EV::orderBy('title', 'asc')->with('type')->get();
        return response()->json($evs);
    }

    public function index()
    {
        $evs = Type::orderBy('type', 'asc')->with('evs')->withCount('evs')->distinct('type')->get();
        return response()->json($evs);
    }

    public function find($title)
    {
         //return gettype($evsItems);
        //var_dump($evsItems);
        $evs = Type::orderBy('type', 'asc')->with('evs')->withCount('evs')->distinct('type')
        ->whereHas('evs', function($q) use($title) {

            $q->whereIn('title',(array)Ev::where('title','like','%'.$title.'%')->pluck('title'));
        })->get()->toArray();

        //var_dump($evs[0]);
        
        //if($evs[0]['evs'][0]['title'] == $title){
        //    return  $title;
        //}
        
        $ev_filtered = $evs;
         $ev_filtered;
        foreach($evs as $ikey => $ev){

            foreach($ev['evs'] as $jkey => $ev2){
               //echo  $ev_filtered[$ikey]['evs'][$jkey]['title']; 
               //$ev_filtered[$ikey]['evs'][$jkey];
               //dd($ev_filtered[$ikey]['evs']);
               unset($ev_filtered[$ikey]['evs'][$jkey]);
            }
        }

        //return response()->json(($ev_filtered));
        foreach($evs as $ikey => $ev){

            foreach($ev['evs'] as $jkey => $ev2){

                if ($ev2['title'] ==  $title) {
                    $ev_filtered[$ikey]['evs'][] = $ev2;
                    
                    return response()->json(($ev_filtered));
                }
            }
        }
        //return response()->json(($ev_filtered));


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
            'pdf' =>       'required',
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
    public function edit($id)
    {
        $ev = Ev::Where('id',$id)->with('type')->first();
        return  response()->json($ev);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' =>       'required',
            'image' =>       'required', //
            'pdf' =>       'required',
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
        $e = EV::find($id);
        $e->title = ucfirst($request->title);
        $e->type_id = $request->type;

        if($request->file('image')){
            $e->image = "/".$image_path;
        }
        if($request->file('pdf')){
            $e->pdf = "/".$pdf_path;
        }
        
        

        


        $e->save();
        if (request()->expectsJson()) {
            return response()->json([
                'message' => "Your item has been submitted successfully"
            ]);
        }

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
