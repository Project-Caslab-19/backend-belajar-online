<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Topic;
use App\Models\LearningProgress;
use App\Models\ClassMember;
use App\Models\QuizResult;
use App\Models\Learning;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use Illuminate\Auth\Access\Response;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    public function get_all_classroom(){
        try{
            $data = Classroom::with('category')->get();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }   

    public function get_detail_classroom($id)
    {
        try{
            $classroom = Classroom::with('topics', 'category', 'members', 'members.user','user')->findOrFail($id);
            return ResponseHelper::responseSuccessWithData($classroom);
        }catch(\Exception $ex){
            return ResponseHelper::responseError('Data not found!', 404);
        }
    }

    public function enroll_classroom(Request $request)
    {
        $user_id = Auth::user()->id;
        $class_id = $request->class_id;
        $token = $request->token;

        $validator = Validator::make($request->all(), [
            'token' => 'required|max:10|min:10',
            'class_id' => 'required'
        ], [], [
            'token' => 'Token',
            'class_id' => 'ID Kelas'
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        $cek = ClassMember::where('user_id', $user_id)->where('class_id', $class_id)->first();
        if(!empty($cek))
        {
            return ResponseHelper::responseValidation('Students are already enrolled in this class!');
        }

        $cek_token = Classroom::where('id', $class_id)->where('token', $token)->first();
        if(empty($cek_token)){
            return ResponseHelper::responseValidation('The class token you entered is incorrect!');
        }   

        try{
            $model = new ClassMember;
            $model->class_id = $class_id;
            $model->user_id = $user_id;
            $model->save();
                
            return ResponseHelper::responseSuccess('Congratulations you have successfully enrolled in this class!');
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function get_class_topics($id)
    {
        $topics = Topic::where('class_id', $id)->with('learnings', 'quizzes')->get();
        $get_status = $this->get_topic_status($topics);
        
        return ResponseHelper::responseSuccessWithData($get_status);
    }

    private function get_topic_status($topics)
    {
        $array = [];
        foreach($topics as $key => $val)
        {
            $theories = [];
            $theories = $this->store_data($val);

            $topic = [
                'id' => $val['id'],
                'name' => $val['name'],
                'theories' => $theories
            ];

            array_push($array, $topic);
        }

        return $array;
    }

    private function store_data($val)
    {
        $theories = [];
        if($val['type'] == 'learning')
        {
            if(!empty($val['learnings']))
            {
                foreach($val['learnings'] as $val2)
                {
                    $check_progress = $this->check_progress_learning($val2['id'], Auth::user()->id);
                    $learning = [
                        'id' => $val2['id'],
                        'name' => $val2['name'],
                        'description' => $val2['description'],
                        'is_open' => $check_progress,
                        'type' => $val['type'],
                        'created_at' => $val2['created_at'],
                    ];
    
                    array_push($theories, $learning);
                }
            }
        }
        else
        {  
            if(!empty($val['quizzes']))
            {
                $check_progress = $this->check_progress_quiz($val['quizzes']['id'], Auth::user()->id);
                $quiz = [
                    'id' => $val['quizzes']['id'],
                    'name' => $val['quizzes']['name'],
                    'description' => $val['quizzes']['description'],
                    'is_open' => $check_progress,
                    'type' => $val['type'],
                    'created_at' => $val['quizzes']['created_at'],
                ];
    
                array_push($theories, $quiz);
            }
        }

        return $theories;
    }

    private function check_progress_learning($learning_id, $user_id)
    {
        try{
            $cek = LearningProgress::where('learning_id', $learning_id)->where('user_id', $user_id)->first();
    
            return (empty($cek)) ? false : true;
        }catch(\Exception $ex)
        {
            return false;
        }
    }

    private function check_progress_quiz($quiz_id, $user_id)
    {
        try{
            $cek = QuizResult::where('quiz_id', $quiz_id)->where('user_id', $user_id)->first();
            return (empty($cek)) ? false : true;
        }catch(\Exception $ex){
            return false;
        }
    }

    public function get_detail_theory($topic_id, $id)
    {
        try{
            $topic = Topic::find($topic_id);
            switch ($topic->type) {
                case 'learning':
                    $data = Learning::where('id', $id)->where('topic_id', $topic_id)->first();
                    break;
                case 'quiz':
                    $data = Quiz::where('id', $id)->where('topic_id', $topic_id)->first();
                    break;
            }

            if(empty($data))
            {
                return ResponseHelper::responseError('data not found!', 404);
            }

            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex)
        {
            return ResponseHelper::responseError($ex, 404);
        }
    }
}
