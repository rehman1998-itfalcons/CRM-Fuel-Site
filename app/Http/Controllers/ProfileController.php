<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function index()
    {
       return view('admin.profile.index');
    }

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

    
    public function show(Request $request, $id)
    {

    }

    
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.profile.edit',compact('user'));
    }

   
    public function update(Request $request, $id)
    {
       $user = User::find($id);
       $this->validate($request, [
          	'name' => 'string',
            'username' => 'alpha_dash|unique:users,username,'.$user->id,
          	'email' => 'email',
        	'profile_picture' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $image = $user->photo;
       
        if ($request->has('profile_picture')) {
          
            if($image){
            unlink('public/uploads/profile/'. $image);
            }
            $file = $request->profile_picture;
            $image1 =  time() . $file->getClientOriginalName();
            $file->move('public/uploads/profile/', $image1);
        }
      else{
         $image1 = $user->photo;
          }
      
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'photo' => $image1,
            ]);
       
        return redirect('profile')->with('success','Profile Updated Successfully.');
    }

    
    public function destroy($id)
    {
        //
    }
}
