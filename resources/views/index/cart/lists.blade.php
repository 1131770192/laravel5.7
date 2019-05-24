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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">2</strong>件商品</span></td>
       <td width="25%" align="center" style=background:#fff url(/index/images/xian.jpg) left center no-repeat;>
        <span class="glyphicon glyphicon-shopping-cart" style=font-size:2rem;color:#666;></span>
       </td>
      </tr>
     </table>
      <table>
        <tr>
          <td><input type="checkbox" id="allbox"> 全选 </td>
        </tr>
      </table>
    @if($data)
    @foreach($data as $v)
     <div class="dingdanlist">
      <table>
       <tr goods_number="{{$v->goods_number}}" goods_id="{{$v->goods_id}}">
        <td width="4%"><input type="checkbox" class="box" /></td>
        <td class="dingimg" width="15%"><img src="{{config('app.img_url')}}{{$v->goods_img}}" /></td>
        <td width="40%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：2015-08-11  13:51</time>
        </td>
        <!-- 小计 -->
        <td style=color:red>¥{{$v->total}}</td>

        <td align="right">
          <input type="button" class="less" value="-" />
          <input type="text" style=width:25px; value="{{$v->buy_number}}" class="buy_number" />  
          <input type="button" class="add" value="+" />
        </td>
        <td><a class="del">删除</a></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
    @endforeach
    @endif

     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<b style=font-size:20px;color:red;>￥<font id="count">0</font></b></td>
       <td width="40%"><a href="javascript:;" id="confirmOrder" class="jiesuan">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
  </body>
</html>
<script src="/index/js/jquery.min.js"></script>
<script>
  $(function(){
    //全选
    $('#allbox').click(function(){
        // alert(111);
        var status=$(this).prop('checked');
        // console.log(status);
        $('.box').prop('checked',status);

        //调用获取商品总价方法
        countTotal();
    })

    //点击+号
    $(document).on('click','.add',function(){
        _this=$(this);
        var buy_number=parseInt(_this.prev('input').val());
        // console.log(buy_number);
        var goods_number=_this.parents('tr').attr('goods_number')
        // console.log(goods_number);
        //控制器改变购买数量
        var goods_id=_this.parents('tr').attr('goods_id');
        // console.log(goods_id);
        //判断是否大于库存
        if(buy_number>=goods_number){
            //把+失效
            _this.prop('disabled',true);
        }else{
            buy_number+=1;
            _this.prev('input').val(buy_number);
            _this.parent().children('input').first().prop('disabled',false);
        }

        //更改购买数量
        changeBuyNumber(goods_id,buy_number);

        //获取小计
        getSubTotal(goods_id,_this);

        //给当前复选框选中
        boxChecked(_this);

        //重新计算总价
        countTotal();
    })

    //点击-号
    $(document).on('click','.less',function(){
        _this=$(this);
        var buy_number=parseInt(_this.next('input').val());
        // console.log(buy_number);
        var goods_number=_this.parents('tr').attr('goods_number')
        // console.log(goods_number);
        //控制器改变购买数量
        var goods_id=_this.parents('tr').attr('goods_id');
        // console.log(goods_id);
        //购买数量<=1
        if(buy_number<=1){
            //把-失效
            _this.prop('disabled',true);
        }else{
            buy_number-=1;
            _this.next('input').val(buy_number);
            _this.parent().children('input').last().prop('disabled',false);
        }

        //更改购买数量
        changeBuyNumber(goods_id,buy_number);

        //获取小计
        getSubTotal(goods_id,_this);

        //给当前复选框选中
        boxChecked(_this);

        //重新计算总价
        countTotal();
    })

    //购买数量 失去焦点
    $(document).on('blur','.buy_number',function(){
        var _this=$(this);
        //改变购买数量
        var buy_number=_this.val();
        var goods_number=_this.parents('tr').attr('goods_number');
        //验证
        var reg=/^\d{1,}$/;
        if(buy_number==''||buy_number<=1||!reg.test(buy_number)){
            _this.val(1);
        }else if(parseInt(buy_number)>=parseInt(goods_number)){
            _this.val(goods_number);
        }else{
            _this.val(parseInt(buy_number));
        }

        //控制器改变购买数量
        var goods_id=_this.parents('tr').attr('goods_id');
        changeBuyNumber(goods_id,buy_number);

        //复选框选中
        boxChecked(_this);

        //改变小计
        getSubTotal(goods_id,_this);

        //获取商品总价
        countTotal();
    })

    //点击复选框
    $(document).on('click','.box',function(){
        //调用获取商品总价方法
        countTotal();
    })

    //获取小计
    function getSubTotal(goods_id,_this)
    {
        $.post(
            "{{url('/cart/getSubTotal')}}",
            {goods_id:goods_id},
            function(res){
                // console.log(res);
                _this.parents('td').prev('td').text('￥'+res);
            }
        );
    }

    //更改购买数量
    function changeBuyNumber(goods_id,buy_number)
    {
        $.ajax({
            url:'/cart/changeBuyNumber',
            method:'post',
            data:{goods_id:goods_id,buy_number:buy_number},
            dataType:'json',
            async:false
            }).done(function(res){
            // console.log(res);
            if(res==2){
              // alert('更改数量失败');
              location.href="{{url('/cart/lists')}}"
            }
        });
    }

    //给当前复选框选中
    function boxChecked(_this)
    {
        _this.parents('tr').find("input[type='checkbox']").prop('checked',true);
    }

    //重新计算总价
    function countTotal()
    {
        //获取所有选中的复选框 对应的商品id
        var _box=$('.box');
        // console.log(_box);
        var goods_id='';
        _box.each(function(index){
            // console.log(index);
            if($(this).prop('checked')==true){
                goods_id+=$(this).parents('tr').attr('goods_id')+',';
            }
        })
        //去掉最后一个‘,’
        goods_id=goods_id.substr(0,goods_id.length-1);
        // console.log(goods_id);
        //把商品id传给控制器 获取商品总价
        $.post(
            "{{url('/cart/countTotal')}}",
            {goods_id:goods_id},
            function(res){
                // console.log(res);
                $('#count').text(res);
            }
        );
    }

    //点击删除
    $('.del').click(function(){
        _this=$(this);
        // console.log(_this);
        var goods_id=_this.parents('tr').attr('goods_id');
        // console.log(goods_id);
        $.post(
            "{{url('/cart/cartDel')}}",
            {goods_id:goods_id},
            function(res){
                // console.log(res);
                if(res==1){
                    //重新获取列表页的数据 或者 把当前一行元素移除
                    _this.parents('tr').remove();
                    
                    //获取商品总价
                    countTotal();
                }else{
                  alert('删除失败');
                }
            }
        );
    })

    //点击确认结算
    $('#confirmOrder').click(function(){
        //获取复选框
        var len=$('.box:checked').length;
        // console.log(len);
        if(len==0){
            alert('请至少选择一件商品');
            return false;
        }
        //获取选中的复选框商品id
        var goods_id='';
        $('.box:checked').each(function(){
            goods_id+=$(this).parents('tr').attr('goods_id')+',';
        });
        goods_id=goods_id.substr(0,goods_id.length-1);
        // console.log(goods_id);
        location.href="{{url('/order/lists')}}?goods_id="+goods_id;
    })
  })
</script>
@endsection