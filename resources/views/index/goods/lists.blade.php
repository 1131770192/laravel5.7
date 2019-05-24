@extends('layouts.shop')
@section('title', 'xxx')
@section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      <img src="{{config('app.img_url')}}{{$data->goods_img}}" />
      <img src="{{config('app.img_url')}}{{$data->goods_img}}" />
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$data->shop_price}}</strong></th>

        <!-- 购买数量 -->
       <td>
        <div>
          <input type="hidden" id="goods_number" value="{{$data->goods_number}}">
          <input type="button" class="less" value="-">
          <input type="text" style=width:30px; value="1" id="buy_number"  />
          <input type="button" class="add" value="+">
        </div>
       </td>

      </tr>
      <tr>
       <td>
        <strong>{{$data->goods_name}}</strong>
        <p class="hui">xx</p>
        <input type="hidden" id="goods_id" value="{{$data->goods_id}}">
       </td>

       <!-- 收藏 -->
       <td align="right">
        <a href="javascript:;" class="shoucang" id="addCollect"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>

      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style=background:none;>订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="{{config('app.img_url')}}{{$data->goods_img}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->

     <table border="1" width="650" height="100">
       <tr style=color:red>
         <th>用户评论</th>
       </tr>
      @if($dataTalk)
      @foreach($dataTalk as $v)
       <tr>
          <td>用户名：
            {{$v->talk_email}}   &nbsp;  &nbsp; &nbsp;  {{$v->talk_grade}}
          </td>
       </tr>
       <tr>
         <td>{{$v->talk_content}}</td>
       </tr>
      @endforeach
      @endif
     </table>
      {{ $dataTalk->links() }}
    <br>
    <br>

    <form action="">
     <table border="1" width="650" height="100">
      <tr style=color:red>
        <th>添加评论</th>
      </tr>
       <tr>
         <td>用户名：</td>
         <td>匿名用户</td>
       </tr>
       <tr>
         <td>email：</td>
         <td><input type="email" class="talk_email" ></td>
       </tr>
       <tr>
         <td>评论等级：</td>
         <td>
          <input type="radio" name="talk_grade" class="talk_grade" value="1星">1星 
          <input type="radio" name="talk_grade" class="talk_grade" value="2星">2星 
          <input type="radio" name="talk_grade" class="talk_grade" value="3星">3星 
          <input type="radio" name="talk_grade" class="talk_grade" value="4星">4星 
          <input type="radio" name="talk_grade" class="talk_grade" value="5星" checked>5星 
         </td>
       </tr>
       <tr>
         <td>评论内容：</td>
         <td>
           <textarea class="talk_content" cols="30" rows="5"></textarea>
         </td>
       </tr>
       <tr>
          <td></td>
          <td><input type="submit" value="提交评论" id="subpl" /></td>
       </tr>
     </table>
    </form>

    <br>
    <br>
    <br>
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
        <input type="submit" value="加入购物车" id="sub" />
       </th>
      </tr>
     </table>
    </div><!--maincont-->

  
  </body>
</html>
<script src="/index/js/jquery.min.js"></script>
<script>
  $(function(){
    //获取库存
    var goods_number=$("#goods_number").val();

    //点击+号
    $('.add').click(function(){
        var buy_number=parseInt($('#buy_number').val());
        // console.log(buy_number);
        //判断是否大于库存
        if(buy_number>=goods_number){
            //把+失效
            $(this).prop('disabled',true);
        }else{
            buy_number+=1;
            $('#buy_number').val(buy_number);
            //-生效
            $('.less').prop('disabled',false);
        } 
    })
    //点击-号
    $('.less').click(function(){
        var buy_number=parseInt($('#buy_number').val());
        //判断是否大于库存
        if(buy_number<=1){
            //把-失效
            $(this).prop('disabled',true);
        }else{
            buy_number-=1;
            $('#buy_number').val(buy_number);
            //+生效
            $('.add').prop('disabled',false);
        }
    })
    //失去焦点
    $('#buy_number').blur(function(){
        var _this=$(this);
        var buy_number=_this.val();
        var reg=/^\d+$/;
        //为空||购买数量<=1||不是数字
        if(buy_number==''||buy_number<=1||!reg.test(buy_number)){
            _this.val(1);
        }else if(parseInt(buy_number)>=parseInt(goods_number)){
            _this.val(goods_number);
        }else{
            //0004=>4
            buy_number=parseInt(buy_number);
            _this.val(buy_number);
        }
    })

    //点击加入购物车
    $('#sub').click(function(){
        var goods_id=$('#goods_id').val();
        // console.log(goods_id);
        var buy_number=$('#buy_number').val();
        // console.log(buy_number);
        if(goods_id==''){
            alert('请选择一件商品');
            return false;
        }
        if(buy_number==''){
            alert('请选择要购买的数量');
            return false;
        }
        //把商品id 购买数量传给控制器
        $.post(
          "{{url('/goods/addcart')}}",
          {goods_id:goods_id,buy_number:buy_number},
          function(res){
            // console.log(res);
            if(res==1){
              location.href="{{url('/cart/lists')}}"
            }else{
              alert('加入购物车失败');
            }
          }
        );
        return false;
    });

    $(function(){
      //点击加入收藏
          $(document).on('click','#addCollect',function(){
              var _this=$(this);
              var goods_id=$('#goods_id').val();
              // console.log(goods_id);
              //把商品id传给控制器
              $.post(
                  "{{url('/goods/addCollect')}}",
                  {goods_id:goods_id},
                  function(res){
                      console.log(res);
                      if(res.code==1){
                          _this.text('已收藏');
                          location.href="{{url('/goods/collectlists')}}"
                      }
                  },
                  'json'
              );
          })
    });


    //点击评论
    $('#subpl').click(function() {
      var talk_email=$('.talk_email').val();
      var talk_grade=$(':checked').val();
      var talk_content=$('.talk_content').val();
      var goods_id=$('#goods_id').val();
      // console.log(talk_email);
      // console.log(talk_grade);
      // console.log(talk_content);
      $.post(
          "{{url('/goods/addtalk')}}",
          {talk_email:talk_email,talk_grade:talk_grade,talk_content:talk_content},
          function(res){
            // console.log(res);
            // if(res==1){
              location.href="/goods/lists/"+goods_id
            // }
          }
        );
      return false;
    });

  })
</script>
@endsection