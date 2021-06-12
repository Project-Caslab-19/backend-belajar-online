<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Learning;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LearningController extends Controller
{
    public function detail($id)
    {
        try{
            $learning = Learning::with('topic')->findOrFail($id);

            return ResponseHelper::responseSuccessWithData($learning);
        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'video' => 'required|mimes:mp4,mov,ogg|max:20000',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        if($request->hasFile('video'))
        {
            try{
                $videoName = time().'.'.$request->video->extension(); 
                $request->video->move(storage_path('uploads/class/learning'), $videoName);

                $path = storage_path('uploads/class/learning/') . $videoName;
            }catch(\Exception $ex){
                return ResponseHelper::responseError('Error when uploading video! '.$ex->getMessage(), 500);
            }

            try{
                Learning::create([
                    'topic_id' => $request->topic_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'video' => $path,
                    'duration' => '0000'
                ]);

                return ResponseHelper::responseSuccess("Success add a learning video!");
            }catch(\Exception $ex){
                return ResponseHelper::responseError($ex->getMessage(), 500);
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'video' => 'required|mimes:mp4,mov,ogg|max:20000',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        if($request->hasFile('video'))
        {
            try{
                $videoName = time().'.'.$request->video->extension(); 
                $request->video->move(storage_path('uploads/class/learning'), $videoName);

                $path = storage_path('uploads/class/learning/') . $videoName;
            }catch(\Exception $ex){
                return ResponseHelper::responseError('Error when uploading video! '.$ex->getMessage(), 500);
            }

            try{
                $edit = Learning::find($id);
                $edit->name = $request->name;
                $edit->description = $request->description;
                if($request->hasFile('video'))
                    $edit->video = $path;
                $edit->save();


                return ResponseHelper::responseSuccess("Success edit a learning video!");
            }catch(\Exception $ex){
                return ResponseHelper::responseError($ex->getMessage(), 500);
            }
        }
    }

    public function delete($id)
    {
        try{
            Learning::destroy($id);

            return ResponseHelper::responseSuccess("Success delete a learning video!");
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }
}
