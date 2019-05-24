<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
// use App\Http\Requests\StoreBrandPost;
use DB;
use App\model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $keywords=request()->keywords;
        $where=[];
        if($keywords){
            $where[]=[
                'cate_name','like',"%$keywords%"
            ];
        }

        $PageSize=config('app.PageSize');
        // $data=DB::table('admin')->where($where)->paginate($PageSize);
        $data=Category::where($where)->paginate($PageSize);
        // dd($data);
        return view('admin.category.lists',['data'=>$data,'keywords'=>$keywords]);
    }

    /**
     * Show the form for creating a new resource.
     *添加
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // echo '添加';
        $res = Category::get();
        $res = $this -> createTree($res);
        // dd($res);
        return view('admin.category.add',['data'=>$res]);
    }

    // 无限极分类
    function createTree($data,$field='cate_id',$parent_id = 0,$level = 1)
    {
        static $result = [];
        if ($data) {
            foreach ($data as $key => $val) {
                if ($val['parent_id'] == $parent_id) {
                    $val['level'] = $level;
                    $result[] = $val;
                    $this -> createTree($data,$field='cate_id',$val[$field],$level+1);
                }
            }
            return $result;
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
        $data=$request->input();
        // dd($data);
        $data['addtime']=time();
        // $res=DB::table('admin')->insert($data);
        $res=Category::insert($data);
        
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *修改
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($cate_id)
    {
        // echo '修改';die;
        $res = Category::where('cate_id','=',$cate_id)->first();
        // dd($res);
        $data2 = Category::get()->toArray();
        // dd($res);
        $data = $this -> createTree($data2);
         // dd($data);
        // dump($res);die;
        // dump($data);die;
        return view('admin/category/edit',compact('res','data'));
    }

    /**
     * Update the specified resource in storage.
     *执行修改
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cate_id)
    {
        //过滤
        $data=$request->except(['_token']);
        // dd($data);
        
        $res=DB::table('category')->where('cate_id',$cate_id)->update($data);
        // $res=Article::where('cate_id','=',$cate_id)->update($data);
        // dd($res);
        if($res){
            return redirect('/category/lists');
        }else{
            return ('修改失败');
        }
    }

    public function checkName()
    {
        $cate_name=request()->cate_name;
        // dd($cate_name);
        if($cate_name){
            $where['cate_name']=$cate_name;
            $count=Category::where($where)->count();
            return ['code'=>1,'count'=>$count];
        }
    }
}
