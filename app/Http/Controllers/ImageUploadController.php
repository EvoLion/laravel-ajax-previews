<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function index()
    {
        return view('image-upload');
    }

    public function save(Request $request)
    { 
        if($request->ajax()){
            session(['preview_images' => $request->previews]);
            return view('components._preview-images',[
                'preview_images' => session('preview_images')
            ])->render();
        }

        return view('image-upload');
    }

    public function delete(Request $request)
    {
        $preview_images = session('preview_images');
        unset($preview_images[$request->previews_to_delete]);
        session(['preview_images' => $preview_images]);
        // var_dump(session('preview_images'));

        return view('components._preview-images',[
            'preview_images' => session('preview_images')
        ])->render();
    }

    public function store(Request $request)
    {
        $final_images = $request->images;

        $files = array_intersect_key($final_images, session('preview_images'));
        // ddd($files);
        
        // $validatedData = $files->validate([
        //     'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        // ]);

        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $path = $file->store('public/images');

            $save = new Photo;
            $save->name = $name;
            $save->path = $path;
            $save->save();
        }
        

        return redirect('image-upload-preview')->with('status', 'Uploaded');
    }
}
