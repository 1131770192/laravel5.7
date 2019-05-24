<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\model\User;
use DB;

class OrderController extends Controller
{
    public function lists()
    {
        $goods_id=request()->goods_id;
        //分割成数组
        $goods_id=explode(',',$goods_id);
        // dd($goods_id);
        //获取用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $cartInfo=DB::table('cart')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->get();
        // print_r($cartInfo);exit;
        $count=0;
        foreach ($cartInfo as $k => $v) {
            $count+=$v->shop_price*$v->buy_number;
        }
        foreach ($cartInfo as $k => $v) {
            $cartInfo[$k]->total=$v->shop_price*$v->buy_number;
        }
        // echo $count;exit;
        
        // 获取小计
        if(!empty($cartInfo)){
            foreach ($cartInfo as $k => $v) {
                $total=$v->shop_price*$v->buy_number;
                $cartInfo[$k]->total=$total;
            }
        }
        
        //获取收货地址信息
        $addressInfo=$this->getAddressInfo();
        // print_r($addressInfo);exit;
        // print_r($cartInfo);exit;
        return view('index/order/lists',compact('count','addressInfo','cartInfo'));
    }

    //查询收货地址列表
    public function getAddressInfo()
    {
        //获取用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        $where=[
            ['is_del','=',1],
            ['user_id','=',$user_id],
            ['is_default','=',1]
        ];
        $addressInfo=DB::table('address')->where($where)->get();
        // dump($addressInfo);exit;
        if(!empty($addressInfo)){
            //处理省市区
            foreach ($addressInfo as $k => $v) {
                $addressInfo[$k]->province=DB::table('Area')->where('id',$v->province)->value('name');
                $addressInfo[$k]->city=DB::table('Area')->where('id',$v->city)->value('name');
                $addressInfo[$k]->area=DB::table('Area')->where('id',$v->area)->value('name');
            }
            return $addressInfo;
        }else{
            return false;
        }
    }

    //点击确认订单
    public function submitOrder()
    {
        // echo "确认订单";die;
        //获取数据
        $goods_id=request()->goods_id;
        //分割成数组
        $goods_id=explode(',',$goods_id);
        $address_id=request()->address_id;
        $pay_type=request()->pay_type;
        // print_r($goods_id);
        // print_r($address_id);
        // print_r($pay_type);die;
        if(empty($goods_id)){
            echo '请选择一件商品';die;
        }
        if(empty($address_id)){
            echo '请选择一个收货地址';die;
        }
        if(empty($pay_type)){
            echo '请选择一个支付方式';die;
        }
        //获取用户id
        $user_id=session('u_id');
        // dd(session('u_id'));
        

        //添加订单表数据
        $order_no=time().rand(100,999).$user_id;//生成订单号
        // echo $order_no;die;
        $order_amount=$this->getOrderAmount($goods_id);//订单总金额
        // echo $order_amount;
        // die;
        $orderInfo['order_no']=$order_no;
        $orderInfo['order_amount']=$order_amount;
        $orderInfo['pay_type']=$pay_type;
        $orderInfo['user_id']=$user_id;
        // print_r($orderInfo);die;
        // insertGetId添加成功，并返回id
        $res1=DB::table('order')->insertGetId($orderInfo);
        // print_r($res1);die;
        session(['order_id'=>$res1]);
        if(empty($res1)){
            //抛出一个异常
            // throw new Exception('订单信息添加失败');
            echo '订单信息添加失败';die;
        }


        //订单收货地址添加
        $addressWhere=[
            ['address_id','=',$address_id],
            ['is_del','=',1]
        ];
        $addressInfo=DB::table('address')->where($addressWhere)->first();
        // print_r($addressInfo);die;
        if(empty($addressInfo)){
            // throw new Exception('没有此收货地址，请重新选择');
            echo '没有此收货地址，请重新选择';die;
        }
        //获取order_id
        $order_id=$res1;
        // echo $order_id;die;
        // unset($addressInfo->update_time);
        // 数组里压入一个order_id
        $addressInfo->order_id=$order_id;
        // print_r($addressInfo);die;
        //对象转换成数组
        $addressInfo=json_decode(json_encode($addressInfo),true);
        //释放与库里字段不一样的字段
        unset($addressInfo['address_id']);
        unset($addressInfo['is_default']);
        // 数组里压入一个id
        $addressInfo['id']=$order_id;
        // print_r($addressInfo);die;
        // $res2=$order_address->allowField(true)->save($addressInfo);
        $res2=DB::table('order_address')->insert($addressInfo);
        // print_r($res2);die;
        if(empty($res1)){
            //抛出一个异常
            // throw new Exception('订单收货地址添加失败');
            echo '订单收货地址添加失败';die;
        }


        //订单表详情添加
        $goodsInfo=$this->getOrderDetail($goods_id);//商品详情信息
        // print_r($goodsInfo);exit;
        foreach ($goodsInfo as $k => $v) {
            $goodsInfo[$k]->order_id=$order_id;
            $goodsInfo[$k]->user_id=$user_id;
        }
        // print_r($goodsInfo);exit;
        if(empty($goodsInfo)){
            //抛出一个异常
            // throw new Exception('没有此商品');
            echo '没有此商品';die;
        }
        //对象转换成数组
        $goodsInfo=json_decode(json_encode($goodsInfo),true);
        // foreach ($goodsInfo as $key => $val) {
        //     unset($goodsInfo[$key]['goods_number']);
        // }
        // print_r($goodsInfo);exit;
        $res3=DB::table('order_detail')->insert($goodsInfo);
        // print_r($res3);die;
        if(empty($res3)){
            //抛出一个异常
            // throw new Exception('订单表详情添加失败');
            echo '订单表详情添加失败';die;
        }


        //删除购物车数据
        $cartWhere=[
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        $res4=DB::table('cart')->where($cartWhere)->whereIn('cart.goods_id',$goods_id)->update(['is_del'=>2]);
        // print_r($res4);die;
        if(empty($res4)){
            //抛出一个异常
            // throw new Exception('删除购物车数据失败');
            echo '删除购物车数据失败';die;
        }


        //减少库存
        // print_r($goodsInfo);exit;
        // //不能多条修改，修改多条要循环修改，有几件商品修改几条
        // foreach ($goodsInfo as $k => $v) {
        //     $update=[
        //         'goods_number'=>$v['goods_number']-$v['buy_number'],
        //     ];
        //     $res5=DB::table('goods')->where('goods_id',$v['goods_id'])->update($update);
        //     if(empty($res5)){
        //         //抛出一个异常
        //         // throw new Exception('减少库存失败');
        //         echo '减少库存失败';die;
        //     }
        // }
        

        //提交
        $arr = [
            'code'=>1,
            'msg'=>'下单成功',
            'order_id'=>$order_id
        ];
        echo json_encode($arr);
    }

    //订单总金额
    public function getOrderAmount($goods_id)
    {
        //获取用户id
        $user_id=session('u_id');
        // print_r($user_id);die;
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $cartInfo=DB::table('cart')
            ->select('shop_price','buy_number')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->get();
        // print_r($cartInfo);die;
        $count=0;
        foreach ($cartInfo as $k => $v) {
            $count+=$v->shop_price*$v->buy_number;
        }
        // echo $count;die;
        return $count;
    }

    //获取商品详情信息
    public function getOrderDetail($goods_id)
    {
        //获取用户id
        $user_id=session('u_id');
        // print_r($user_id);die;
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $goodsInfo=DB::table('cart')
            ->select('goods.goods_id','goods_name','goods_img','shop_price','buy_number')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->get();
        // print_r($goodsInfo);die;
        return $goodsInfo;
    }

    //下单成功
    public function successOrder()
    {
        // echo "下单成功";die;
        $order_id=request()->order_id;
        // echo $order_id;die;
        //验证订单号
        if(empty($order_id)){
            echo '请选择正确订单号';die;
        }
        //获取用户id
        $user_id=session('u_id');
        // print_r($user_id);die;
        $where=[
            ['user_id','=',$user_id],
            ['order_id','=',$order_id],
            ['is_del','=',1]
        ];
        $orderInfo=DB::table('order')->where($where)->first();
        // print_r($orderInfo);die;
        if(empty($orderInfo)){
            // throw new \Exception('没有此订单信息');
            echo '没有此订单信息';die;
        }
        return view('index/order/successOrder',compact('orderInfo'));
    }


    //电脑端支付
    public function pcpay()
    {
        $user_id=session('u_id');
        // print_r($user_id);die;
        $order_id=session('order_id');
        // print_r($order_id);die;
         $where=[
            ['user_id','=',$user_id],
            ['order_id','=',$order_id],
        ];
        $res=DB::table('order')->where($where)->first();
        // print_r($res);die;
        $config=config('alipay');
        require_once app_path('libs\pcalipay\pagepay\service\AlipayTradeService.php');
        require_once app_path('libs\pcalipay\pagepay\buildermodel\AlipayTradePagePayContentBuilder.php');
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $res->order_no;

        //订单名称，必填
        $subject = '商品';

        //付款金额，必填
        $total_amount = $res->order_amount;

        //商品描述，可空
        $body = '';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response); 
    }
    //验签
    public function returnpay()
    {
        $config=config('alipay');
         require_once app_path('libs\pcalipay\pagepay\service\AlipayTradeService.php');
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $where['order_no'] = htmlspecialchars($_GET['out_trade_no']);

            //商户总金额
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);


            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            $count=DB::table('order')->where($where)->count();
            //所有的数据
            if($count){
                 echo "支付宝交易号：".$trade_no."订单号:".$where['order_no']."订单金额:".$where['order_amount']."此订单有问题";exit;
            }
            //商家id
            if(config('pay.seller_id')!=htmlspecialchars($_GET['seller_id'])){
                 echo "支付宝交易号：".$trade_no."订单号:".$where['order_no']."订单金额:".$where['order_amount']."此订单有问题,商户id不一致";exit;
            }
            //app_id
            if(config('pay.app_id')!=htmlspecialchars($_GET['app_id'])){
                 echo "支付宝交易号：".$trade_no."订单号:".$where['order_no']."订单金额:".$where['order_amount']."此订单有问题,app_id不一致";exit;
            }
           
            echo "验证成功<br />支付宝交易号：".$trade_no;
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "验证失败";
        }
    }


    //手机端支付
    public function phonepay()
    {
        echo '手机';die;
        
    }
}

?>