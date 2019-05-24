@extends('layouts.shop')
@section('title', 'xxx')
@section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
       <tr>
        <td class="dingimg" width="75%" colspan="2"><a href="{{url('address/add')}}">新增收货地址</a></td>
        <td align="right"><img src="/index/images/jian-new.png" /></td>
       </tr>
       <tr><td colspan="3" style=height:10px; background:#efefef;padding:0;></td></tr>
       <tr>
        <input type="hidden" id="address_id" value="{{$addressInfo[0]->address_id}}">
        <td class="dingimg" colspan="3">
          收货地址：{{$addressInfo[0]->province}} {{$addressInfo[0]->city}} {{$addressInfo[0]->area}} {{$addressInfo[0]->address_detail}} {{$addressInfo[0]->address_name}} {{$addressInfo[0]->address_tel}}
        </td>
       </tr>
       <tr><td colspan="3" style=height:10px; background:#efefef;padding:0;></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right">
          <select>
            <option value="1" id="pay_type" selected="selected">支付宝</option>
          </select>
        </td>
       </tr>
       <tr><td colspan="3" style=height:10px; background:#efefef;padding:0;></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
       <tr><td colspan="3" style=height:10px; background:#efefef;padding:0;></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
        <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票抬头</td>
        <td align="right"><span class="hui">个人</span></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票内容</td>
        <td align="right"><a href="javascript:;" class="hui">请选择发票内容</a></td>
       </tr>
       <tr><td colspan="3" style=height:10px; background:#fff;padding:0;></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
       @if($cartInfo)
       @foreach($cartInfo as $v)
       <tr class="goods_id" goods_id="{{$v->goods_id}}">
        <td class="dingimg" width="15%"><img src="{{config('app.img_url')}}{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：2015-08-11  13:51</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->total}}</strong></th>
       </tr>
       @endforeach
       @endif
       
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$count}}</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥0.00</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$count}}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
  </body> 
</html>

@endsection
<script src="/index/js/jquery.min.js"></script>
<script>
    $(function(){
      //点击确认订单
      $('.jiesuan').click(function(){
          //获取商品id
          var goods_id='';
          $('.goods_id').each(function(index){
              //拼接
              goods_id+=$(this).attr('goods_id')+',';
          })
          // console.log(goods_id);
          //去掉后方，
          goods_id=goods_id.substr(0,goods_id.length-1);
          // console.log(goods_id);
          //获取收货地址id
          var address_id=$('#address_id').val();
          // console.log(address_id);
          //获取支付方式
          var pay_type=$("#pay_type").val();
          // console.log(pay_type);
          $.post(
              "{{url('/order/submitOrder')}}",
              {goods_id:goods_id,address_id:address_id,pay_type:pay_type},
              function(res){
                  // console.log(res);
                  if(res.code==1){
                      location.href="{{url('/order/successOrder')}}?order_id="+res.order_id
                  };
              },
              'json'
          );


      })

    })
</script>