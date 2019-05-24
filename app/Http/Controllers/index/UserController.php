<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\User;
use DB;

class UserController extends Controller
{
    public function create()
    {
    	// echo 11;die;
        
        return view('index/user/add');
    }

    //邮箱唯一
    public function checkName()
    {
    	// echo 1;die;
        $u_email=request()->u_email;
        // dd($u_email);
        if($u_email){
            $where['u_email']=$u_email;
            $count=DB::table('user')->where($where)->count();
            return ['code'=>1,'count'=>$count];
        }
    }

    //发送邮件
    public function emailsend()
    {
    	$u_email=request()->u_email;
    	$rand=rand(1000,9999);
    	session(['u_email'=>$u_email]);
    	session(['rand'=>$rand]);
        // dd($u_email);
		$res=$this->send($u_email,$rand);
		if(!$res){
			return ['code'=>1,'count'=>'发送成功'];
		}else{
			return ['code'=>0,'count'=>'发送失败'];
		}
    }
    public function send($u_email,$rand){
        \Mail::raw('验证码：'.$rand ,function($message)use($u_email){
        	
	        //设置主题
	        $message->subject("欢迎注册");
	        //设置接收方
	        $message->to($u_email);
        });
	}

	//验证码唯一
    public function checkYzm()
    {
    	// echo 1;die;
        $u_code=request()->u_code;
        // dd($u_code);
        if($u_code==session('rand')){
            return ['code'=>1];
        }else{
        	return ['code'=>0];
        }
    }

    /**
     * Store a newly created resource in storage.
     *执行添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    // public function store(StoreBrandPost $request)
    {
    	// echo '添加';die;
        $data=$request->except(['u_pwd1']);
        // dd($data);
        $data['create_time']=time();
        // $res=DB::table('admin')->insert($data);
        $res=User::insert($data);
        
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }

    //登录页面
    public function login()
    {
    	// echo 11;die;
    	return view('index/user/login');
    }
    public function dologin()
    {
    	$u_email=request()->u_email;
    	$u_pwd=request()->u_pwd;
    	$where=[
	        'u_email'=>$u_email
	    ];
	    $userInfo=DB::table('user')->where($where)->first();
	    // print_r($userInfo);die;
        $u_id=$userInfo->u_id;
	    if(empty($userInfo)){
	    	// echo "用户错误";
	    	return ['code'=>0,'count'=>'用户或密码错误'];
	    }else{
	    	// echo "用户正确";
	    	if($userInfo->u_pwd==$u_pwd){
	    		// echo "密码正确";
	    		session(['u_id'=>$u_id]);
	    		return ['code'=>1,'count'=>'登陆成功'];
	    	}else{
	    		// echo "密码错误";
	    		return ['code'=>0,'count'=>'用户或密码错误'];
	    	}
	    	
	    }
    }


    //个人中心
    public function index()
    {
        // echo "个人中心";die;
        if(session('u_id')==null){
            return redirect('/user/login');
        }else{
            return view('index/user/index');
        }
    }
    //退出
    public function logout()
    {
        request()->session()->forget('u_id');
        return redirect('/');
    }
}

?>