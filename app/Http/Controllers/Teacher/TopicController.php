<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use Exception;
use GrahamCampbell\ResultType\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    public function get_all()
    {
        $topics = Topic::all();
        return ResponseHelper::responseSuccessWithData($topics);
    }

    public function get_class_topic($class_id)
    {
        $topics = Topic::with(['learnings', 'quizzes'])->where('class_id', $class_id)->get();
        return ResponseHelper::responseSuccessWithData($topics);
    }

    public function get_detail($id)
    {
        $topic = Topic::with(['classroom', 'learnings', 'quizzes'])->findOrFail($id);
        return ResponseHelper::responseSuccessWithData($topic);
    }

    public function get_class_topics($id){
        $topics = Topic::where('class_id', $id);
        return ResponseHelper::responseSuccessWithData($topics);
    }

    public function create_topic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'type' => 'required'
        ], [], [
            'type' => 'Type',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        try{
            Topic::create([
                'class_id' => $request->class_id,
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
            ]);

            return ResponseHelper::responseSuccess("Success create a Class Topic");
        }catch(Exception $ex)
        {
            ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }

    public function edit_topic(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required'
        ], [], [
            'type' => 'Type',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        try{
            $topic = Topic::findOrFail($id);
            $topic->name = $request->name;
            $topic->description = $request->description;
            $topic->type = $request->type;
            $topic->save();

            return ResponseHelper::responseSuccess("Success edit a Class Topic");
        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }

    public function delete_topic($id)
    {
        try{
            $topic = Topic::withCount(['learnings', 'quizzes'])->findOrFail($id);

            if($topic->learnings_count > 0 || $topic->quizzes_count > 0)
            {
                return ResponseHelper::responseValidation("Cannot deleting Topic because the topic has theory");
            }

            try{
                $topic->destroy($id);

                return ResponseHelper::responseSuccess("Success delete a Class Topic");
            }catch(Exception $ex){
                return ResponseHelper::responseError($ex->getMessage(), 500);
            }

        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }
}
