<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use DB;

class LogsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function index()
    {
        $activity_logs = Log::get();
        return view('admin.logs.index', compact('activity_logs'));
    }
    
    public function show($username)
    {
        $user = DB::table('users')->where('username', $username)->first();
        $activity_logs = Log::where('user_id', $user->id)->get();
        return view('admin.logs.show', compact('activity_logs', 'user'));
    }
}