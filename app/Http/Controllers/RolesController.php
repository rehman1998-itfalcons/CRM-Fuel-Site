<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use Session;
use Str;

class RolesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('superadmin');
    }  
 
  
    
    public function index()
    {
        $roles = Role::get();
        return view('admin.roles.index', compact('roles'));
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        Role::create([
            'name' => $request->name,
          	'slug' => Str::slug($request->name)
        ]);

        Session::flash('success','Role added successfully.');
        return redirect('/roles');
    }


   
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $role = Role::find($request->id);
        $role->update([
          'name' => $request->name,
          'slug' => Str::slug($request->name)
        ]);

        Session::flash('success','Role updated successfully.');
        return redirect('/roles');
    }

   
    public function destroy(Request $request, $id)
    {
      
    }
}
