@extends('layouts.shop')
@section('title', 'xxx')
@section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form action="login.html" method="get" class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" id="address_name" placeholder="收货人" /></div>
       <div class="lrList"><input type="text" id="address_detail" placeholder="详细地址" /></div>
       <div class="lrList">
        <select class="changearea" id="province">
         <option value="0" selected="selected">--请选择--</option>
         @if($provinceInfo)
         @foreach($provinceInfo as $v)
            <option value="{{$v->id}}">{{$v->name}}</option>
         @endforeach
         @endif
        </select>
       </div>
       <div class="lrList">
        <select class="changearea" id="city">
         <option value="0" selected="selected">--请选择--</option>
        </select>
       </div>
       <div class="lrList">
        <select class="changearea" id="area">
         <option value="0" selected="selected">--请选择--</option>
        </select>
       </div>
       <div class="lrList"><input type="text" id="address_tel" placeholder="手机" /></div>
      </div><!--lrBox/-->

      <input type="checkbox" id="is_default">设置为默认收货地址

      <div class="lrSub">
       <input type="submit" class="doadd" value="保存" />
      </div>
     </form><!--reg-login/-->
     
@include('public/footer')

@endsection
<script src="/index/js/jquery.min.js"></script>
<script>
  $(function(){
    //内容改变
    $('.changearea').change(function() {
      var _this=$(this);
      // _this.nextAll('select').html("<option value='0'>--请选择--</option>");
      var id=_this.val();
      // console.log(id);
      $.post(
        "{{url('/address/getArea')}}",
        {id:id},
        function(res){
          // console.log(res);
          var _option="<option value='0'>--请选择--</option>";
          for (var i = 0;i<res.length; i++) {
            _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>"
          }
          // console.log(_option);
          _this.parent("div[class='lrList']").next("div").children('select').html(_option);
        },
        'json'
      );
    })

    //点击添加
    $('.doadd').click(function() {
      var obj={};
      obj.province=$('#province').val();
      obj.city=$('#city').val();
      obj.area=$('#area').val();
      obj.address_name=$('#address_name').val();
      obj.address_tel=$('#address_tel').val();
      obj.address_detail=$('#address_detail').val();
      // console.log(obj);
      var is_default=$('#is_default').prop('checked');
      if(is_default==true){
        obj.is_default=1;
      }else{
        obj.is_default=2;
      }
      // console.log(obj);
      
      $.post(
        "{{url('/address/addressDo')}}",
        obj,
        function(res){
          // console.log(res);
          if(res==1){
              location.href="{{url('/address/lists')}}"
          }else{
            alert('失败');
            return false;
          }
        }
      );
      return false;
    });
    
  });
</script>