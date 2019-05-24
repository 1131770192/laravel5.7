<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改分类</title>
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
				<img src="/admin/img/coin02.png" /><span><a href="#">首页</a>&nbsp;-&nbsp;<a
					href="#">公共管理</a>&nbsp;-</span>&nbsp;意见管理
			</div>
		</div>
		<div class="page ">
			<!-- 会员注册页面样式 -->
		  <form action="/category/update/{{$res->cate_id}}" method="post">
		  	@csrf

			<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

			<div class="banneradd bor">
				<div class="baTopNo">
					<span>商品分类</span>
				</div>
				<div class="baBody">
					<div class="bbD">
						分类名称：<input type="text" class="input3 cate_name" name="cate_name" value="{{$res->cate_name??''}}" />
					</div>
					<div class="bbD">
						父级分类：
						<select class="input3" name="parent_id">
							<option value="">请选择分类</option>

							@foreach($data as $v)
							@if($v['cate_id'] == $res['cate_id'])
							<option value="{{$v['parent_id']}}" selected>
							{{str_repeat(" - ",$v['level'])}}{{$v['cate_name']}}
							</option>
							@else
							<option value="{{$v['parent_id']}}">
							{{str_repeat(" - ",$v['level'])}}{{$v['cate_name']}}
							</option>
							@endif
							@endforeach

						</select>
					</div>
					<div class="bbD">
						是否显示：
						<label><input type="radio" class="is_show" name="is_show" value="1" {{$res['is_show']==1?'checked':''}} />&nbsp;是</label>
						<label><input type="radio" class="is_show" name="is_show" value="0" {{$res['is_show']==0?'checked':''}} />&nbsp;否</label>
					</div>
					<div class="bbD">
						描述：
						<div class="btext">
							<textarea class="text1 keywords" name="keywords">{{$res->keywords??''}}</textarea>
						</div>
					</div>
					<p><b style=color:red; id="error"></b></p>
					<div class="bbD">
						<p class="bbDP">
							<button class="btn_ok btn_yes">提交</button>
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
<script>
	$(function(){
		$(".btn_yes").click(function(){
			// alert(7);
			var cate_name=$('.cate_name').val();
			var keywords=$('.keywords').val();
			var is_show=$('.is_show:checked').val();
			// var parent_id=$('');
			if(cate_name==''){
				$('#error').text('分类名不能为空');
				return false;
			}
			if(keywords==''){
				$('#error').text('描述不能为空');
				return false;
			}
		 	// alert(parent_id);
			
			//基于 AJAX 的请求提供了简单、方便的方式来避免 CSRF 攻击： 
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			// $.post(
			// 	"{{url('/category/doadd')}}",
			// 	{cate_name:cate_name,keywords:keywords,is_show:is_show},
			// 	function(res){
			// 		// console.log(res);
			// 		if(res==1){
			// 			location.href="{{url('/category/lists')}}"
			// 		}
			// 	}
			// );
			// return false;

		})
	})	
</script>