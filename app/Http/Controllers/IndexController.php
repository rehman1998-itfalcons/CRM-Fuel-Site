<?php

namespace App\Http\Controllers;


class IndexController extends Controller
{
    
    public function __construct()
    {
		$this->middleware('guest')->except('logout');    
    } 
  
  	public function index()
    {
    	return view('index');
    }
  
}