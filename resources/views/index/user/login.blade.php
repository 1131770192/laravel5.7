@extends('layouts.shop')
@section('title', 'xxx')
@section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员登录</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form action="" method="" class="reg-login">
      <h3>还没有三级分销账号？点此<a class="orange" href="{{url('user/add')}}">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" class="u_email" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList"><input type="text" class="u_pwd"  placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" class="sub" value="立即登录" />
      </div>
     </form><!--reg-login/-->
@include('public/footer')
<script src="/index/js/jquery.min.js"></script>
<script>
  $(function(){
    //点击获取
    $(".sub").click(function(){
        //获取邮箱
        var u_email=$(".u_email").val();
        var reg=/^\w+@\w+\.com$/;
        var u_pwd=$('.u_pwd').val();

        //验证
        if(u_email==''){
          alert('邮箱必填');
          return false;
        }
        if(!reg.test(u_email)){
          alert('请输入正确邮箱格式');
          return false;
        }
        if(u_pwd==''){
          alert('密码必填');
          return false;
        }

        $.post(
          "{{url('/user/dologin')}}",
          {u_email:u_email,u_pwd:u_pwd},
          function(res){
            // console.log(res);
            if(res.code==1){
              alert(res.count);
              location.href="{{url('/')}}"
            }else{
              alert(res.count);
              return false;
            }
          },
          'json'
        );
      return false;
    });

  });
</script>
@endsection    
