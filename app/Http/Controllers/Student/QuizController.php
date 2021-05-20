<?php

namespace App\Http\Controllers\Student;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function get_questions($id_quiz)
    {
        try{
            $data = Question::select(['id', 'quiz_id', 'value', 'key_answer'])->with('answers')->with('quiz_answer', function($q) use($id_quiz)
            {
                $q->where('user_id', Auth::user()->id);
            })->where('quiz_id', $id_quiz)->get();
        }catch(\Exception $ex)
        {   
            return ResponseHelper::responseError($ex, 500);
        }

        return ResponseHelper::responseSuccessWithData($data);
    }

    public function send_answer(Request $req)
    {
        $user_id = Auth::user()->id;
        $question_id = $req->question_id;
        $answer_id = $req->answer_id;

        $select_answer = Answer::where('question_id', $question_id)->where('id', $answer_id)->first();
        if(empty($select_answer))
        {
            return ResponseHelper::responseError('data not found!', 404);
        }

        try{
            $cek = QuizAnswer::where('user_id', $user_id)->where('question_id', $question_id)->first();
            if(empty($cek))
            {
                $create = QuizAnswer::create([
                    'user_id' => $user_id,
                    'question_id' => $question_id,
                    'answer_id' => $answer_id
                ]);
            }
            else
            {
                $cek->answer_id = $req->answer_id;
                $cek->save();
            }
        }catch(\Exception $ex)
        {  
            return ResponseHelper::responseError($ex, 500);
        }

        return ResponseHelper::responseSuccess('Success to send answer!');
    }
}
