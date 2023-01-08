<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    
    public function index()
    {
      return view('admin/change_password.index');
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'new_password' => ['required'],
            'new_confirm_password' => ['required'],
        ]);


         $user = User::where('id',\Auth::id())->where('hint', $request->old_password)->first();

        if($user){

                if ($request->new_password != $request->new_confirm_password) {
                    return redirect()->back()->with('error', 'This is my error');
                }
                else{
                    $user->update([
                        'password' => Hash::make($request->new_password),
                        'hint' => $request->new_password,
                    ]);
                    return redirect()->back()->with('correct', 'your password is renewed successfully!');
                }
            }
        else{
            return redirect()->back()->with('incorrect_old_password', 'your old password is incorrect!');
        }
    }
}
