<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\model\User;
use DB;

class GoodsController extends Controller
{
    public function lists()
    {
        //接收商品id
        $goods_id=request()->goods_id;
        // dd($goods_id);
        if(empty($goods_id)){
            echo "请选择一个商品";exit;
        }
        //根据商品id查询一条商品数据
        $data=DB::table('goods')->where('goods_id','=',$goods_id)->first();
        // print_r($goodsInfo);die;
        
        //评论
        $page=request()->page??1;
        $dataTalk=cache('page_'.$page);
        if(!$dataTalk){
            echo "db";
            $PageSize=config('app.PageSize');
            $dataTalk=DB::table('talk')->orderBy('talk_id','desc')->paginate($PageSize);
            // print_r($dataTalk);die;
            cache(['page_'.$page=>$dataTalk],5);
        }
    	return view('index/goods/lists',compact('data','dataTalk'));
    }

    //添加评论
    public function addtalk()
    {
        // echo "评论";die;
        $data=request()->all();
        // print_r($data);//die;
        $result=DB::table('talk')->insert($data);
        // dd($result);
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    //加入购物车
    public function addcart()
    {
        //接受商品id  购买数量
        $goods_id=request()->goods_id;
        $buy_number=request()->buy_number;
        // dump($goods_id);
        // dump($buy_number);exit;
        $goodsInfo=DB::table('goods')->where('goods_id',$goods_id)->first();
        // print_r($goodsInfo);die;
        $shop_price=$goodsInfo->shop_price;
        // print_r($shop_price);die;
        //验证
        if(empty($goods_id)){
            echo '请选择一件商品';die;
        }
        if(empty($buy_number)){
            echo '请选择要购买的数量';die;
        }
        //根据用户id，商品id判断用户是否买过此商品
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            'user_id'=>$user_id,
            'goods_id'=>$goods_id,
            'is_del'=>1
        ];
        // dd($where);
        $cartInfo=DB::table('cart')->where($where)->first();
        // print_r($cartInfo);die;
        if(!empty($cartInfo)){
            // echo "累加";die;
            //用户买过之后 判断库存 累加
            // print_r($cartInfo);die;
            $number=$cartInfo->buy_number;
            // echo $number;
            //根据id查询商品库存
            $goods_number=DB::table('goods')->where('goods_id',$goods_id)->value('goods_number');
            // echo $goods_number;exit;
            //监测商品库存
            if($buy_number+$number>$goods_number){
                echo "超过库存";die;
            }
            // echo $buy_number;die;
            //没超库存执行修改数据
            $updateInfo=[
                //已加入购车的数量+将要购买数量
                'buy_number'=>$number+$buy_number
            ];
            // print_r($updateInfo);
            $result=DB::table('cart')->where($where)->update($updateInfo);
        }else{
            // echo "添加";die;
            //没买过 判断库存 添加
            //根据id查询商品库存
            $goods_number=DB::table('goods')->where('goods_id',$goods_id)->value('goods_number');
            // echo $goods_number;exit;
            //监测商品库存
            if($buy_number>$goods_number){
                echo "超过库存";die;
            }
            $info=[
                'goods_id'=>$goods_id,
                'buy_number'=>$buy_number,
                'user_id'=>$user_id
            ];
            // print_r($info);die;
            $result=DB::table('cart')->insert($info);
        }
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    //所有商品
    public function prolists()
    {
        return view('index/goods/prolists');
    }

    //加入收藏
    public function addCollect()
    {
        // echo "加入收藏";die;
        $goods_id=request()->goods_id;
        // echo $goods_id;die;
        //判断是否登录
        //获取用户id
        $u_id=session('u_id');
        // dd($user_id);
        $where=[
            'u_id'=>$u_id,
            'goods_id'=>$goods_id
        ];
        $info=DB::table('collect')->where($where)->get();
        //对象转换成数组
        $info=json_decode(json_encode($info),true);
        // print_r($info);die;
        if(!empty($info)){
            $message=[
                'font'=>'已收藏',
                'code'=>0
            ];
            echo json_encode($message);exit;
            // echo 0;die;
        }else{
            // echo "收藏成功";die;
            $result=DB::table('collect')->insert($where);
            if($result){
                $message=[
                    'font'=>'收藏成功',
                    'code'=>1
                ];
                echo json_encode($message);
            }else{
                echo '收藏失败';die;
            }
        }
    }
    public function collectlists()
    {
        // echo "收藏";die;
        return view('index/goods/collectlists');
    }
    
}

?>