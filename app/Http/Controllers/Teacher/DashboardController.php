<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\ClassMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_dashboard_content(){
        $user_id = Auth::user()->id;
        try{
            $data = ClassMember::with('classroom')->where('user_id', $user_id)->get();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }
}
