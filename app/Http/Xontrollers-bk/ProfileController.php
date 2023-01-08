<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('admin.profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.profile.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        // Item Image Upload
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
