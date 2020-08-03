<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Image;

class ResizeController extends Controller
{
    function index()
    {
     return view('resize');
    }

    function resize_image(Request $request)
    {
     $this->validate($request, [
      'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
     ]);

     $image = $request->file('image');
     $image_name = time() . '.' . $image->getClientOriginalExtension();
     $destinationPath = public_path('/thumbnail');
     $resize_image = Image::make($image->getRealPath());
    
     $percentage = 0.66;
     $resize_image->resize($resize_image->width() * $percentage, null, function ($constraint) {
        $constraint->aspectRatio();
     })->save($destinationPath . '/' . $image_name);

     $destinationPath = public_path('/images');

     $image->move($destinationPath, $image_name);

     return back()
       ->with('success', 'Zdjęcie zostało dodane pomyślnie')
       ->with('imageName', $image_name);

    }
}