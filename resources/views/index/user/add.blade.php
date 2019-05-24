@extends('layouts.shop')
@section('title', 'xxx')
@section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form action="login.html" method="get" class="reg-login">
      <h3>已经有账号了？点此<a class="orange" href="{{url('user/login')}}">登陆</a></h3>
      <div class="lrBox">

        <meta name="csrf-token" content="{{ csrf_token() }}">

       <div class="lrList"><input type="text" name="u_email" class="u_email" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList2">
        <input type="text" name="u_code" class="u_code" placeholder="输入短信验证码" />
          <a class="btn" href="javascript:void(0);" id="sendEmailCode">
              <span style=color:red;font-size:20px; id="span_email">获取</span>
          </a>
       </div>
       <div class="lrList"><input type="password" name="u_pwd" class="u_pwd" placeholder="设置新密码（6-18位数字或字母）" /></div>
       <div class="lrList"><input type="password" name="u_pwd1" class="u_pwd1" placeholder="再次输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" class="sub" value="立即注册" />
      </div>
     </form><!--reg-login/-->

     @include('public/footer')

    @endsection
<script src="/index/js/jquery.min.js"></script>
<script>
  $(function(){
    //点击获取
    $("#sendEmailCode").click(function(){
        //获取邮箱
        var u_email=$(".u_email").val();
        var reg=/^\w+@\w+\.com$/;
        var flag=false;

        //验证
        if(u_email==''){
          alert('邮箱必填');
          return false;
        }else if(!reg.test(u_email)){
          alert('请输入正确邮箱格式');
          return false;
        }else{
          //基于 AJAX 的请求提供了简单、方便的方式来避免 CSRF 攻击： 
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          //把邮箱传给控制器
          $.ajax({
            method:'post',
            url:'/user/checkName',
            dataType:'json',
            async:false,
            data:{u_email:u_email}
          }).done(function(res){
            // console.log(res);
            if(res.count){
              alert('邮箱已存在');
              flag=false;
            }else{
              flag=true;
            }
          });
          if(flag!=true){
            return flag;
          }
        }

        //秒数倒计时
        $("#span_email").text(30+'s');
        setI=setInterval(timeLess,1000);

        //把邮箱传给控制器  控制器发送邮件
        $.post(
          "{{url('/user/emailsend')}}",
          {u_email:u_email},
          function(res){
            // console.log(res);
            if(res.code==1){
              alert(res.count);
            }else{
              alert(res.count);
            }
          },
          'json'
        );
        
    });
    //倒计时
    function timeLess(){
      var _time=parseInt($("#span_email").text());
      if(_time<=0){
        $("#span_email").text('获取');
        clearInterval(setI);
            //允许点击
            $('#sendEmailCode').css("pointerEvents","auto");
        }else{
          //秒数减一
          _time=_time-1;
          $("#span_email").text(_time+'s');
            //不允许点击
            $('#sendEmailCode').css("pointerEvents","none");
        }
    }

    //验证码失去焦点
    $('.u_code').blur(function() {
      var u_code=$(this).val();
      var flag=false;
      $.ajax({
        method:'post',
        url:'/user/checkYzm',
        dataType:'json',
        async:false,
        data:{u_code:u_code}
      }).done(function(res){
        // console.log(res);
        if(!res.code==1){
          alert('验证码错误');
          flag=false;
        }else{
          flag=true;
        }
      });
      if(flag!=true){
        return flag;
      }
    });

    //点击注册
    $('.sub').click(function(){
      //获取邮箱
        var u_email=$(".u_email").val();
        var reg=/^\w+@\w+\.com$/;
        var u_code=$('.u_code').val();
        var u_pwd=$(".u_pwd").val();
        var u_pwd1=$(".u_pwd1").val();
        var flag=false;

        //验证
        if(u_email==''){
          alert('邮箱必填');
          return false;
        }else if(!reg.test(u_email)){
          alert('请输入正确邮箱格式');
          return false;
        }else{
          //把邮箱传给控制器
          $.ajax({
            method:'post',
            url:'/user/checkName',
            dataType:'json',
            async:false,
            data:{u_email:u_email}
          }).done(function(res){
            // console.log(res);
            if(res.count){
              alert('邮箱已存在');
              flag=false;
            }else{
              flag=true;
            }
          });
          if(flag!=true){
            return flag;
          }
        }

        if(u_code==''){
          alert('验证码必填');
          return false;
        }
        if(u_pwd==''){
          alert('密码必填');
          return false;
        }
        if(u_pwd1==''){
          alert('确认密码必填');
          return false;
        }
        if(u_pwd!=u_pwd1){
          alert('确认密码必须与密码一致');
          return false;
        }

        $.post(
          "{{url('/user/store')}}",
          {u_email:u_email,u_code:u_code,u_pwd:u_pwd},
          function(res){
            if(res==1){
              location.href="{{url('/user/login')}}"
            }else{
              alert('失败');
            }
          }
        );
      return false;
    })

  })
</script>