<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use Session;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('superadmin');
    }  
 
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('deleted_status',0)->get();
        return view('admin.users.index', compact('users'));
    }
  
  	public function trashBox()
    {
        $users = User::where('deleted_status',1)->get();
        return view('admin.users.trash', compact('users'));      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::select('id','name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required',
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email',
            'password' => 'required'
        ]);

      	$name = '';
      	if ($request->has('photo'))
        {
          $this->validate($request, [
            'photo' => 'required|mimes:jpeg,png,jpg,gif,svg'
          ]);
          
          $file = $request->photo;
          $name = time().$file->getClientOriginalName();
          $file->move('public/uploads/profile',$name);
        }
      
      	$attachments = '';
      	if ($request->has('attachments')) {
          	$this->validate($request, [
            	'attachments.*' => 'required|mimes:pdf'
          	]);
          	$attachments = [];
        	foreach ($request->attachments as $attachment) {
            	$nam = time().$attachment->getClientOriginalName();
          		$attachment->move('public/uploads',$nam);
              	array_push($attachments,$nam);
            }
          	$attachments = implode("::",$attachments);
        }

        User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hint' => $request->password,
          	'photo' => $name,
            'account_status' => 1,
          	'deleted_status' => 0,
          	'attachments' => $attachments
        ]);

        Session::flash('success','User added successfully.');
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('username',$id)->first();
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('username',$id)->first();
        $roles = Role::select('id','name')->get();
        return view('admin.users.edit', compact('user','roles'));
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
            'role_id' => 'required',
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,'.$user->id,
            'email' => 'required|email',
            'password' => 'required'
        ]);
      	$name = $user->photo;
      	if ($request->has('photo'))
        {
          $this->validate($request, [
            'photo' => 'required|mimes:jpeg,png,jpg,gif,svg'
          ]);
          
          unlink('public/uploads/profile/'.$name);
          $file = $request->photo;
          $name = time().$file->getClientOriginalName();
          $file->move('public/uploads/profile',$name);
        }
    
      $attachments = ($user->attachments) ? explode("::",$user->attachments) : [];
      	if ($request->has('attachments')) {
          	$this->validate($request, [
            	'attachments.*' => 'required|mimes:pdf'
          	]);
          
        	foreach ($request->attachments as $attachment) {
            	$nam = time().$attachment->getClientOriginalName();
          		$attachment->move('public/uploads',$nam);
              	array_push($attachments,$nam);
            }
          
      		$attachments = implode("::",$attachments);
        } else {
          	$attachments = implode("::",$attachments);
        }
       
   
      
      $user->update([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hint' => $request->password,
          	'photo' => $name,
            'account_status' => 1,
          	'deleted_status' => 0,
          	'attachments' => $attachments
        ]);
      

        Session::flash('success','User updated successfully.');
        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
     //   $user = User::find($request->id);
     // 	if ($user->photo) {
     //     if (file_exists('public/uploads/profile/'.$user->photo))
     //       unlink('public/uploads/profile/'.$user->photo);
     //   }
     // 	$user->delete();
        
     //   Session::flash('success','User permanently deleted.');
        Session::flash('success','Its under development');
        return redirect('/users');
    }
  
  	public function validateUsername(Request $request)
    {
      $user = User::where('username',$request->username)->first();
      if ($user)
        return '1';
      else
        return '0';
    }
  
  	public function validateEditUsername(Request $request)
    {
      $user = User::where('username',$request->username)->first();
      if ($user) {
        if ($user->id != $request->id)
        	return '1';
        else
          return '0';
      } else
        return '0';
    }
  
  	public function banUser(Request $request)
    {
      $user = User::find($request->id);
      if ($request->type == 'ban')
        $user->update(['account_status' => $request->status]);
      else
        $user->update(['account_status' => $request->status]);
      
      Session::flash('success','Action performed successfully.');
      return back();
    }
  
  	public function trashUser(Request $request)
    {
      $user = User::find($request->id);
      if ($request->type == 'trash')
        $user->update(['deleted_status' => $request->status]);
      else
        $user->update(['deleted_status' => $request->status]);
      
      Session::flash('success','Action performed successfully.');
      return back();
    }
  
  	public function deleteAttachment(Request $request)
    {
    	$user = User::find($request->id);
      	$attachments = explode("::",$user->attachments);
      
      	foreach ($attachments as $key => $value) {
          	if ($value == $request->attachment) {
            	unset($attachments[$key]);
              	//unlink('public/uploads/'.$value);
          	}
        }

      	$user->update([
        	'attachments' => implode("::",$attachments)
        ]);
      
      	Session::flash('success','Attachment Deleted');
      	return back();
    }
}
