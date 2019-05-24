<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandPost;
use DB;
use Illuminate\Support\Facades\Auth;
use App\model\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo '展示';
        $page=request()->page??1;
        // dd($page);
        $query=request()->all();
        $brand_name=$query['brand_name']??'';
        $brand_url=$query['brand_url']??'';
        $data=cache('list_'.$page.'_'.$brand_name.'_'.$brand_url);
        if(!$data){
            echo "db";
            $where=[];
            if($brand_name){
                $where[]=[
                    'brand_name','like',"%$brand_name%"
                ];
            }
            if($brand_url){
                $where['brand_url']=$brand_url;
            }

            $PageSize=config('app.PageSize');
            // DB::connection()->enableQueryLog();
            // $data=DB::table('brand')->where($where)->paginate($PageSize);
            $data=Brand::where($where)->orderBy('brand_id','desc')->paginate($PageSize);
            // $logs = DB::getQueryLog();
            // dd($logs);
            cache(['list_'.$page.'_'.$brand_name.'_'.$brand_url=>$data],5);
        }
        return view('admin.brand.lists',['data'=>$data,'query'=>$query]);
    }
    
    public function show($brand_id)
    {
      $data=cache('data_'.$brand_id);
      // dd($data);
      if(!$data){
        echo "db";
        $data=Brand::find($brand_id);
        cache(['data_'.$brand_id=>$data],5);
      }
      
      // print_r($data);die;
      return view('admin.brand.show',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *添加
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // echo '添加';
        return view('admin.brand.add');
    }

    /**
     * Store a newly created resource in storage.
     *执行添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(StoreBrandPost $request)
    {
        //过滤
        $data=$request->except(['_token']);
        // dd($data);
        
      /*验证1
        $validatedData = $request->validate([
            'brand_name' => 'required|unique:brand|max:10',
            'brand_desc' => 'required',
            'brand_url' => 'required'
        ],[
            'brand_name.required' =>'品牌名不能为空',
            'brand_name.unique' =>'品牌名不能重复',
            'brand_name.max' =>'品牌名不能超过10字节',
            'brand_desc.required' =>'品牌描述不能为空',
            'brand_url.required' =>'品牌网址不能为空'
        ]);
      */
     
      /*验证2
        $validator = \Validator::make($request->all(), [
            'brand_name' => 'required|unique:brand|max:10',
            'brand_desc' => 'required',
            'brand_url' => 'required'
        ],[
            'brand_name.required' =>'品牌名不能为空',
            'brand_name.unique' =>'品牌名不能重复',
            'brand_name.max' =>'品牌名不能超过10字节',
            'brand_desc.required' =>'品牌描述不能为空',
            'brand_url.required' =>'品牌网址不能为空'
        ]);
 
        if ($validator->fails()) {
            return redirect('brand/add')
            ->withErrors($validator)
            ->withInput();
        }
      */

        //文件上传
        if($request->hasFile('brand_logo')){
            $res=$this->upload($request,'brand_logo');
            if($res['code']){
                $data['brand_logo']=$res['imgurl'];
            }
        }
        // dd($data);

        // $res=DB::table('brand')->insert($data);
        $res=Brand::insert($data);
        if($res){
            return redirect('/brand/lists');
        }else{
            return error('添加失败','/brand/add');
        }
    }
    public function upload(Request $request,$file)
    {
        if($request->file($file)->isValid()){
            $photo = $request->file($file);
            // dump($photo);die;
            // $extension = $photo->extension();
            $store_result = $photo->store(date('Ymd'));
            // dump($store_result);die;
            // $store_result = $photo->storeAs('photo', 'test.jpg');
            
            return ['code'=>1,'imgurl'=>$store_result];
        }else{
            return ['code'=>0,'message'=>'上传过程出错'];
        }
    }

    /**
     * Display the specified resource.
     *发送邮件
     * @return \Illuminate\Http\Response
     */
    public function sendemail(){
        $email =  request()->email;
        
        $this->send($email);
        
    }
    public function send($email){
        // \Mail::raw('hello' ,function($message)use($email){
        \Mail::send('email' ,['name'=>$email],function($message)use($email){
        //设置主题
            $message->subject("欢迎注册");
        //设置接收方
            $message->to($email);
        });
    }

    /**
     * Display the specified resource.
     *登录
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $email =  request()->email;
        $password =  request()->password;
        // dump($email);
        // dd($password);
        if(Auth::attempt(['email'=>$email,'password'=>$password])){
            // dump(Auth::user());
            // dd(Auth::id());
            return '登录成功';
        }else{
            return '登录失败';
        }
    }

    /**
     * Show the form for editing the specified resource.
     *修改
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($brand_id)
    {
        // echo '修改';
        $data = DB::table('brand')->where('brand_id', '=', $brand_id)->first();
        // dump($data);die;
        return view('/Brand/edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *执行修改
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $brand_id)
    {
        //过滤
        $data=$request->except(['_token']);
        dd($data);
    }

    /**
     * Remove the specified resource from storage.
     *删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($brand_id)
    {
        // echo '删除';
        // dd($brand_id);
        $res=DB::table('brand')->where('brand_id','=',$brand_id)->delete();
        if($res){
            return redirect('/brand/lists');
        }else{
            return error('删除失败','/brand/lists');
        }
    }
}
