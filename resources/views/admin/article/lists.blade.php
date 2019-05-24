<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/page.css')}}">
        <script type="text/javascript" src="/admin/js/jquery.min.js"></script>
    </head>
    <body>
        <form>
            文章标题<input type="text" name="article_title" value="{{$query['article_title']??''}}" placeholder="请输入关键字">
            <button>搜索</button>
        </form>
        <a href="{{url('article/add')}}">返回添加</a>
        <br>

        <meta name="csrf-token" content="{{ csrf_token() }}">

      <div id="con">
    		<table border="1">
    			<tr align="center">
            <td>id</td>
    				<td>文章标题</td>
    				<td>文章分类</td>
    				<td>文章重要性</td>
            <td>是否显示</td>
            <td>添加日期</td>
            <td colspan="2">操作</td>
    			</tr>
          @if($data)
          @foreach($data as $v)
          <tr align="center" article_id={{$v->article_id}}>
              <td>{{$v->article_id}}</td>
              <td><a href="suibian/{{$v->article_id}}">{{$v->article_title}}</a></td>
              <td>{{$v->c_name}}</td>
              <td>{{$v->is_important}}</td>
              <td>{{$v->is_show}}</td>
              <td>{{$v->addtime}}</td>
              <td class="del">
                  <a href="javascript:;">删除</a>
              </td>
              <td>
                  <a href="edit/{{$v->article_id}}">修改</a>
              </td>
          </tr>
          @endforeach
          @endif
          {{ $data->appends($query)->links() }}
    		</table>
        
      </div>
    </body>
</html>
<script>
  $(function(){
      //点击删除
      $(document).on('click','.del',function(){
          var _this=$(this);
          var article_id=_this.parents('tr').attr('article_id');
          // alert(article_id);
          
          //基于 AJAX 的请求提供了简单、方便的方式来避免 CSRF 攻击： 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

          //把商品id传给控制器
          $.post(
              "{{url('/article/destroy')}}",
              {article_id:article_id},
              function(res){
                  // console.log(res);
                  if(res==1){
                      //把当前一行元素移除
                      _this.parents('tr').remove();
                      // location.href="{{url('/article/lists')}}"
                  }
              }
          );   
      })

      //分页
      $(document).on('click','.pagination a',function(){
        // alert(11);
        var url=$(this).attr('href');
        $.get(url,function(data){
          $('#con').html(data);
        })
        return false;
      })

  });
</script>