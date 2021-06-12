<?php

namespace App\Http\Controllers\Student;

use App\Models\Quiz;
use App\Models\Topic;
use App\Models\Learning;
use App\Models\Classroom;
use App\Models\QuizResult;
use App\Models\ClassMember;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\LearningProgress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function get_all_classroom()
    {
        try{
            $data = Classroom::with('category')->get();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex->getMessage(), 500);
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
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }

    public function get_class_topics($id)
    {
        $topics = Topic::where('class_id', $id)->with('learnings', 'quizzes')->get();
        $get_status = $this->getTopicStatus($topics);
        
        return ResponseHelper::responseSuccessWithData($get_status);
    }

    private function getTopicStatus($topics)
    {
        $array = [];
        foreach($topics as $key => $val)
        {
            $theories = [];
            $theories = $this->storeData($val);

            $topic = [
                'id' => $val['id'],
                'name' => $val['name'],
                'theories' => $theories
            ];

            array_push($array, $topic);
        }

        return $array;
    }

    private function storeData($val)
    {
        $theories = [];
        if($val['type'] == 'learning')
        {
            if(!empty($val['learnings']))
            {
                foreach($val['learnings'] as $val2)
                {
                    $check_progress = $this->checkProgressLearning($val2['id'], Auth::user()->id);
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
                foreach($val['quizzes'] as $val2)
                {
                    $check_progress = $this->checkProgressQuiz($val2['id'], Auth::user()->id);
                    $quiz = [
                        'id' => $val2['id'],
                        'name' => $val2['name'],
                        'description' => $val2['description'],
                        'is_open' => $check_progress,
                        'type' => $val['type'],
                        'created_at' => $val2['created_at'],
                    ];
        
                    array_push($theories, $quiz);
                }
            }
        }

        return $theories;
    }

    private function checkProgressLearning($learning_id, $user_id)
    {
        try{
            $cek = LearningProgress::where('learning_id', $learning_id)->where('user_id', $user_id)->first();
        }catch(\Exception $ex)
        {
            return false;
        }

        return (empty($cek)) ? false : true;
    }

    private function checkProgressQuiz($quiz_id, $user_id)
    {
        try{
            $cek = QuizResult::where('quiz_id', $quiz_id)->where('user_id', $user_id)->first();
            return (empty($cek)) ? false : true;
        }catch(\Exception $ex){
            return false;
        }
    }

    public function get_video($learning_id)
    {
        $data = Learning::find($learning_id);

        $file = File::get($data->video);
        $type = File::mimeType($data->video);

        return (new Response($file, 200))->header('Content-Type', $type);
    }

    public function get_detail_theory($topic_id, $id)
    {
        try{
            $topic = Topic::find($topic_id);
            switch ($topic->type) {
                case 'learning':
                    $data = $this->getDetailLearning($id, $topic_id);
                    break;
                case 'quiz':
                    $data = $this->getDetailQuiz($id, $topic_id);
                    break;
            }

            if(empty($data))
            {
                return ResponseHelper::responseError('data not found!', 404);
            }

            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 404);
        }
    }

    private function getDetailLearning($id, $topic_id)
    {
        $data = Learning::where('id', $id)->where('topic_id', $topic_id)->first();
        LearningProgress::create([
            'topic_id' => $topic_id,
            'user_id' => Auth::user()->id,
            'duration' => '00'
        ]);

        return $data;
    }

    private function getDetailQuiz($id, $topic_id)
    {
        $quiz = Quiz::where('id', $id)->where('topic_id', $topic_id)->first();
        $quiz_results = QuizResult::where('user_id', Auth::user()->id)->where('quiz_id', $quiz->id)->get();

        $data = [
            'id' => $quiz->id,
            'topic_id' => $quiz->topic_id,
            'name' => $quiz->name,
            'description' => $quiz->description,
            'created_at' => $quiz->created_at,
            'quiz_results' => $quiz_results,
        ];

        return $data;
    }

    public function get_classroom_progress($id)
    {
        $learnings = $this->getLearningsProgress($id);
        $quizzes = $this->getQuizzesProgress($id);
        
        $trueResultLearnings = count(array_filter($learnings));
        $falseResultLearnings = count($learnings) - $trueResultLearnings;
        $trueResultQuizzes = count(array_filter($quizzes));
        $falseResultQuizzes = count($quizzes) - $trueResultQuizzes;

        $total_complete = $trueResultLearnings + $trueResultQuizzes;
        $total_uncomplete = $falseResultLearnings + $falseResultQuizzes;

        $total_theory = count($learnings) + count($quizzes);
        $percentage = 100 * ($total_complete / $total_theory);
        
        $data = [
            'total_theory' => $total_theory,
            'total_complete' => $total_complete,
            'total_uncomplete' => $total_uncomplete,
            'percentage' => $percentage
        ];

        return ResponseHelper::responseSuccessWithData($data);
    }

    private function getLearningsProgress($id)
    {
        $learnings = Learning::with('topic')->whereHas('topic', function($q) use($id){
            $q->where('class_id', $id);
        })->get();

        $progress = [];
        foreach ($learnings as $key => $learning) {
            $get_progress = $this->checkProgressLearning($learning->id, Auth::user()->id);
            array_push($progress, $get_progress);
        }

        return $progress;
    }

    private function getQuizzesProgress($id)
    {
        $quizzes = Quiz::with('topic')->whereHas('topic', function($q) use($id){
            $q->where('class_id', $id);
        })->get();

        $progress = [];
        foreach ($quizzes as $key => $quiz) {
            $get_progress = $this->checkProgressQuiz($quiz->id, Auth::user()->id);
            array_push($progress, $get_progress);
        }

        return $progress;
    }

    public function get_complete_class()
    {
        $data = $this->getUserClassroom('complete');

        return ResponseHelper::responseSuccessWithData($data);
    }

    public function get_uncomplete_class()
    {
        $data = $this->getUserClassroom('uncomplete');

        return ResponseHelper::responseSuccessWithData($data);
    }

    private function getUserClassroom($status = null)
    {
        $datas = Classroom::with('members')->whereHas('members', function($q){
            $q->where('user_id', Auth::user()->id);
        })->get();

        $data = [];
        foreach($datas as $val)
        {
            $class_id = $val->id;
            $class_name = $val->name;
            $class_description = $val->description;
            $class_created_at = $val->created_at;
            $class_progress = $this->get_classroom_progress($class_id)->original['data'];
            $class_progress_percentage = $this->get_classroom_progress($class_id)->original['data']['percentage'];

            $arr = [
                'id' => $class_id,
                'name' => $class_name,
                'description' => $class_description,
                'created_at' => $class_created_at,
                'progress' => $class_progress,
            ];

            if($status == 'complete')
            {
                if($class_progress_percentage === 100)
                {
                    array_push($data, $arr);
                }
            }
            elseif($status == 'uncomplete')
            {
                if($class_progress_percentage < 100)
                {
                    array_push($data, $arr);
                }
            }
            else
            {
                array_push($data, $arr);
            }
        }

        return $data;
    }

    public function get_classroom_quiz()
    {
        $user_id = Auth::user()->id;
        $quizzes = Quiz::with('topic', 'topic.classroom', 'topic.classroom.members' ,'quiz_results')->whereHas('topic.classroom.members', function($q) use($user_id){
            $q->where('user_id', $user_id);
        })->whereHas('quiz_results', function($q) use($user_id){
            $q->where('user_id', $user_id);
        })->get();

        $data = [];
        foreach($quizzes as $val)
        {
            $highestQuizResult = $this->getHighestQuizResult($val['quiz_results']);
            
            $arr = [
                'id' => $val['id'],
                'name' => $val['name'],
                'description' => $val['description'],
                'topic' => $val['topic']['name'],
                'classroom' => $val['topic']['classroom']['name'],
                'quiz_results' => $highestQuizResult,
            ];

            array_push($data, $arr);
        }

        return ResponseHelper::responseSuccessWithData($data);
    }

    private function getHighestQuizResult($quiz_results)
    {
        $highest_index = 0;
        $highest_value = 0;
        foreach ($quiz_results as $key => $val) {
            if($val['value'] > $highest_value)
            {
                $highest_value = $val['value'];
                $highest_index = $key;
            }
        }

        return $quiz_results[$highest_index];
    }
}
