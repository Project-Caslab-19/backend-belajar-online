<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function get_all()
    {
        $topics = Topic::all();
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
}
