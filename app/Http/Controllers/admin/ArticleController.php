<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use DB;
use App\model\Clas;
use App\model\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *展示
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query=request()->article_title;

        $page=request()->page??1;
        $data=cache('page_'.$page.'_'.$query);
        // dd($data);
        if(!$data){
            echo "111";
            $where=[];
            if($query['article_title']??''){
                $where[]=[
                    'article_title','like',"%$query[article_title]%"
                ];
            }

            $PageSize=config('app.PageSize');
            $data=Article::join('clas','article.c_id','=','clas.c_id')->where($where)->orderBy('article_id','desc')->paginate($PageSize);
            // $data=Article::where($where)->orderBy('article_id','desc')->paginate($PageSize);
            cache(['page_'.$page.'_'.$query=>$data],5);
            // dd($data);
        }
        
        if(request()->ajax()){
            return view('admin.article.ajaxlists',['data'=>$data,'query'=>$query]);
        }
        return view('admin.article.lists',['data'=>$data,'query'=>$query]);
    }

    public function suibian($article_id)
    {
        $data=cache('data_'.$article_id);
        if(!$data){
            echo 11;
            $data=Article::where('article_id',$article_id)->first();
            // dd($data);
            cache(['data_'.$article_id=>$data],5);
        }
        
        return view('admin.article.suibian',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *添加
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // echo '添加';
        $res = Clas::get();
        // dd($res);
        return view('admin.article.add',['data'=>$res]);
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
        $data=$request->except(['_token']);
        // dd($data);

        //验证
        $validator = \Validator::make($request->all(), [
            'article_title' => 'required|unique:article',
            'c_id' => 'required'
        ],[
            'article_title.required' =>'标题不能为空',
            'article_title.unique' =>'标题不能重复',
            'c_id.required' =>'分类不能为空'
        ]);
 
        if ($validator->fails()) {
            return redirect('article/add')
            ->withErrors($validator)
            ->withInput();
        }

        //文件上传
        if($request->hasFile('article_logo')){
            $res=$this->upload($request,'article_logo');
            if($res['code']){
                $data['article_logo']=$res['imgurl'];
            }
        }

        $data['addtime']=time();
        $res=Article::insert($data);
        // dd($data);
        if($res){
            return redirect('/article/lists');
        }else{
            return error('添加失败','/article/add');
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

    public function destroy(Request $request)
    {
        // echo '删除';die;
        // dd($request);
        $article_id=$request->article_id;
        // dd($article_id);
        $where=[
            ['article_id','=',$article_id]
        ];
        $res=DB::table('article')->where($where)->delete();
        // dump($res);die;
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
    public function edit($article_id)
    {
        // echo '修改';die;
        $data = DB::table('article')->where('article_id', '=', $article_id)->first();
        $res=DB::table('clas')->get();
        // dump($data);die;
        return view('admin/article/edit',compact('data','res'));
    }

    /**
     * Update the specified resource in storage.
     *执行修改
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $article_id)
    {
        //过滤
        $data=$request->except(['_token']);
        // dd($data);

        //验证
        $validator = \Validator::make($data, [
            'article_title' => [
                'required',
                Rule::unique('article')->ignore($article_id,'article_id'),
            ],
            'c_id' => 'required'
        ],[
            'article_title.required' =>'标题不能为空',
            'article_title.unique' =>'标题不能重复',
            'c_id.required' =>'分类不能为空'
        ]);
        if ($validator->fails()) {
            return redirect('article/edit/'.$article_id)
            ->withErrors($validator)
            ->withInput();
        }

        //文件上传
        if($request->hasFile('article_logo')){
            $res=$this->upload($request,'article_logo');
            if($res['code']){
                $data['article_logo']=$res['imgurl'];
            }
        }

        $res=DB::table('article')->where('article_id',$article_id)->update($data);
        // $res=Article::where('article_id','=',$article_id)->update($data);
        // dd($res);
        if($res){
            return redirect('/article/lists');
        }else{
            return ('修改失败');
        }

    }
}
