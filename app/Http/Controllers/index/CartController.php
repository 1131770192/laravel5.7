<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\model\User;
use DB;

class CartController extends Controller
{
    public function lists()
    {
        // echo "购物车";die;
        //根据用户id查询商品id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        // dd($where);
        $data=DB::table('cart')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($where)
            ->orderBy('cart_id')
            ->get();
        // print_r($data);exit;
        
        // 获取小计
        if(!empty($data)){
            foreach ($data as $k => $v) {
                $total=$v->shop_price*$v->buy_number;
                $data[$k]->total=$total;
            }
        }

    	return view('index/cart/lists',['data'=>$data]);
    }

    //获取小计
    public function getSubTotal()
    {
        // echo "小计";die;
        $goods_id=request()->goods_id;
        // echo $goods_id;exit;
        //获取商品价格
        $goodsWhere=[
            ['goods_id','=',$goods_id]
        ];
        $shop_price=DB::table('goods')->where($goodsWhere)->value('shop_price');
        // print_r($shop_price);die;
        //获取商品购买数量
        //根据用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $cartWhere=[
            ['goods_id','=',$goods_id],
            ['user_id','=',$user_id]
        ];
        $buy_number=DB::table('cart')->where($cartWhere)->value('buy_number');
        // print_r($buy_number);die;
        echo $shop_price*$buy_number;
    }

    //更改购买数量
    public function changeBuyNumber()
    {
        // echo "更改购买数量";die;
        $goods_id=request()->goods_id;
        $buy_number=request()->buy_number;
        // echo $goods_id;
        // echo $buy_number;exit;
        //根据id查询商品库存
        $goods_number=DB::table('goods')->where('goods_id',$goods_id)->value('goods_number');
        // echo $goods_number;exit;
        //监测商品库存
        if($buy_number>$goods_number){
            echo "超过库存";die;
        }

        //获取用户id
        $user_id=session('u_id');
        // echo $user_id;die;
        $where=[
            'goods_id'=>$goods_id,
            'user_id'=>$user_id
        ];
        $updateInfo=[
            'buy_number'=>$buy_number
        ];
        $result=DB::table('cart')->where($where)->update($updateInfo);
        // dump($result);die;
        if($result){
            echo 1;
        }else{
            echo 2;
        }  
    }

    //重新计算总价
    public function countTotal()
    {
        // echo "重新计算总价";die;
        $goods_id=request()->goods_id;
        $goods_id=explode(',',$goods_id);
        // print_r($goods_id);exit;
        //获取用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            'user_id'=>$user_id
        ];
        // print_r($where);exit;
        $info=DB::table('cart')
            ->select('shop_price','buy_number')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->get();
        // print_r($info);exit;
        $count=0;
        foreach ($info as $k => $v) {
            $count+=$v->shop_price*$v->buy_number;
        }
        echo $count;
    }

    //点击删除，批删
    public function cartDel()
    {
        $goods_id=request()->goods_id;
        // print_r($goods_id);die;
        //获取用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            ['user_id','=',$user_id],
            //单删、批删用in
            ['goods_id','=',$goods_id]
        ];
        $updateWhere=[
            'is_del'=>2
        ];
        $res=DB::table('cart')->where($where)->update($updateWhere);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }

}

?>