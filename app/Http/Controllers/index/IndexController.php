<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {	
    	$res=DB::table('category')->where('parent_id','=',0)->get();
    	// dd($res);
    	$data=DB::table('goods')->get();
    	// dd($data);
    	return view('index/index',compact('res','data'));
    }
    
}
