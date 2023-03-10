<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{

    public function index(){
        $slider = Slider::all();
        return response()->json([
            'status' => 200,
            'slider' => $slider
        ]);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required|min:5',
            'description' => 'required',
            'image' => 'required|image|mimes: jpeg,png,jpg'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errorsMessage' => $validator->messages()
            ]);
        }else{
            $slider = new Slider();
            $slider->name = $req->input('name');
            $slider->description = $req->input('description');
            if($req->hasFile('image')){
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('uploads/slider/', $filename);
                $slider->image = 'uploads/slider/'.$filename;
            }


            $slider->save();
            return response()->json([
                'status' => 200,
                'message' => "Add slider successfully"
            ]);
        }
    }
    public function edit($id){
        $slider = Slider::find($id);
        if($slider){
            return response()->json([
                'status' => 200,
                'slider' => $slider
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Not found slider"
            ]);
        }
    }
    public function update(Request $req,  $id){
        $validator = Validator::make($req->all(), [
            'name' => 'required|min:5',
            'description' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errorsMessage' => $validator->messages()
            ]);
        }else{
            $slider = Slider::find($id);
            if($slider){
                $slider->name = $req->input('name');
                $slider->description = $req->input('description');
                if($req->hasFile('image')){
                    $path = $slider->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $req->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;
                    $file->move('uploads/product/', $filename);
                    $slider->image = 'uploads/product/'.$filename;
                }

                $slider->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Update slider successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found'
                ]);
            }
        }
    }
}

