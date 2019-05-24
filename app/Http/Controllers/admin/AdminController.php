<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminPost;
use DB;
use App\model\Admin;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $value = request()->session()->get('key');
        // $res = $request->session()->flush();
        // dump($res);
        
        // Cookie::queue('author', 'xx', 12);
        // $res=Cookie::get('author');
        // dd($res);
        // Cookie::queue(Cookie::forget('author'));
        
        // echo '展示';
        $keywords=request()->keywords;
        $where=[];
        if($keywords){
            $where[]=[
                'admin_name','like',"%$keywords%"
            ];
        }

        $PageSize=config('app.PageSize');
        // $data=DB::table('admin')->where($where)->paginate($PageSize);
        $data=Admin::where($where)->paginate($PageSize);
        // dd($data);
        return view('admin.admin.lists',['data'=>$data,'keywords'=>$keywords]);
    }

    /**
     * Show the form for creating a new resource.
     *添加
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // session(['key' => 'xx']);
        // echo '添加';
        return view('admin.admin.add');
    }

    /**
     * Store a newly created resource in storage.
     *执行添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(StoreAdminPost $request)
    {
        //过滤
        $data=$request->except(['repwd']);
        dd($data);
        $data['addtime']=time();
        // $res=DB::table('admin')->insert($data);
        $res=Admin::insert($data);
        
        if($res){
            // return redirect('/admin/lists');
            return ['code'=>1,'msg'=>'成功'];
        }else{
            // return error('添加失败','/admin/add');
            return ['code'=>0,'msg'=>'失败'];
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
        echo '修改';
        
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
        
    }

    /**
     * Remove the specified resource from storage.
     *删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($brand_id)
    {
        echo '删除';
        
    }
}
