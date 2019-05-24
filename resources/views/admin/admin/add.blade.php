<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加用户</title>
<link rel="stylesheet" type="text/css" href="/admin/css/css.css" />
<script type="text/javascript" src="/admin/js/jquery.min.js"></script>
</head>
<body>
	@if ($errors->any())
    <div class="alert alert-danger">         
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
	<div id="pageAll">
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" /><span><a href="#">首页</a>&nbsp;-&nbsp;<a
					href="#">公共管理</a>&nbsp;-</span>&nbsp;意见管理
			</div>
		</div>
		<div class="page ">
			<!-- 会员注册页面样式 -->
		  <form action="/admin/doadd" method="post">
		  	<!-- @csrf -->

		  	<meta name="csrf-token" content="{{ csrf_token() }}">

			<div class="banneradd bor">
				<div class="baTopNo">
					<span>会员注册</span>
				</div>
				<div class="baBody">
					<div class="bbD">
						用户名：<input type="text" class="input3 admin_name" name="admin_name" />
					</div>
					<div class="bbD">
						上传头像：<input type="file" name="admin_logo" />
					</div>
					<div class="bbD">
						密码：<input type="password" class="input3 pwd" name="pwd" />
					</div>
					<div class="bbD">
						确认密码：<input type="password" class="input3 repwd" name="repwd" />
					</div>
					<div class="bbD">
						邮箱：<input type="email" class="input3 email" name="email" />
					</div>
					<p><b style=color:red; id="error"></b></p>
					<div class="bbD">
						<p class="bbDP">
							<input type="button" class="btn_ok btn_yes" value="提交" />
							<a class="btn_ok btn_no">取消</a>
						</p>
					</div>
				</div>
			</div>
		  </form>
			<!-- 会员注册页面样式end -->
		</div>
	</div>
</body>
</html>
<!-- <script>
	$(function(){
		$('.btn_yes').click(function(){
		    var admin_name=$('.admin_name').val();
		    if(admin_name==''){
		      $('#error').text('用户名不能为空');
		      return false;
		    }
		    var pwd=$('.pwd').val();
		    if(pwd==''){
		      $('#error').text('密码不能为空');
		      return false;
		    }
		    var repwd=$('.repwd').val();
		    if(repwd==''){
		      $('#error').text('确认密码不能为空');
		      return false;
		    }
		    var email=$('.email').val();
		    if(email==''){
		      $('#error').text('邮箱不能为空');
		      return false;
		    }
		    if(admin_name&&pwd&&repwd&&email){
		      $('form').submit();
		    }
		});
	})
</script> -->
<!-- <script>
	$(function(){
		$(".btn_yes").click(function(){
			// alert(7);
			var admin_name=$('.admin_name').val();
			var pwd=$('.pwd').val();
			var repwd=$('.repwd').val();
			var email=$('.email').val();
			// var _token=$("input:hidden").val();
			if(admin_name==''){
				$('#error').text('用户名不能为空');
				return false;
			}
			if(pwd==''){
				$('#error').text('密码不能为空');
				return false;
			}
		 	if(repwd==''){
				$('#error').text('确认密码不能为空');
				return false;
			}
			if(email==''){
				$('#error').text('邮箱不能为空');
				return false;	
			}
			// console.log(admin_name);
			// console.log(pwd);
			// console.log(repwd);
			// console.log(email);
			// console.log(_token);
			
			//基于 AJAX 的请求提供了简单、方便的方式来避免 CSRF 攻击： 
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.post(
				"{{url('/admin/doadd')}}",
				{admin_name:admin_name,pwd:pwd,repwd:repwd,email:email},
				function(res){
					// console.log(res);
					if(res==1){
						location.href="{{url('/admin/lists')}}"
					}
				}
			);
			return false;

		})
	})	
</script> -->
<script>
	$(function(){
		$(".btn_yes").click(function(){
			// alert(1);
			var fd = new FormData($('#tf')[0]);
			// console.log(fd);
			$.ajax({
				method: 'post',
				url: '/admin/doadd',
				data: fd,
				dataType: 'json',
				processData: false,
				contentType: false,
			})
			.done(function(msg) {
				console.log(msg);
				// if(msg.code==1){

				// }else{
				// 	alert(msg.data.brand_name);
				// 	alert(msg.data.brand_name);
				// 	alert(msg.data.brand_name);
				// 	return false;
				// }
			});
			
		})
	})
</script>