<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="">
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
    	<form action="/article/doadd" method="post" enctype="multipart/form-data">
            @csrf
    		<table>
    			<tr>
    				<td>文章标题：</td>
    				<td><input type="text" class="article_title" name="article_title"></td>
    			</tr>
                <tr>
                    <td>文章分类：</td>
                    <td>
                        <select name="c_id" id="c_name">
                            <option value="">请选择分类</option>
                            @foreach($data as $v)
                            <option value="{{$v->c_id}}">{{$v->c_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>文章重要性：</td>
                    <td>
                        <input type="radio" class="is_important" name="is_important" value="普通" checked />普通
                        <input type="radio" class="is_important" name="is_important" value="置顶" />置顶
                    </td>
                </tr>
                <tr>
                    <td>是否显示：</td>
                    <td>
                        <input type="radio" class="is_show" name="is_show" value="是" checked />是
                        <input type="radio" class="is_show" name="is_show" value="否" />否
                    </td>
                </tr>
                <tr>
                    <td>文章作者：</td>
                    <td><input type="text" class="article_name" name="article_name"></td>
                </tr>
                <tr>
                    <td>作者email：</td>
                    <td><input type="email" class="article_email" name="article_email"></td>
                </tr>
                <tr>
                    <td>关键字：</td>
                    <td><input type="text" class="keywords" name="keywords"></td>
                </tr>
    			<tr>
    				<td>网页描述：</td>
                    <td><textarea name="article_desc" class="article_desc" cols="30" rows="10"></textarea></td>
    			</tr>
                <tr>
                    <td>上传文件</td>
                    <td><input type="file" name="article_logo"></td>
                </tr>
                <tr>
                    <td><button class="sub">提交</button></td>
                </tr>
    		</table>
            <a href="{{url('article/lists')}}">展示</a>
    	</form>
    </body>
</html>
<script>
    $(function(){
        $(".sub").click(function(){
            // alert(7);
            var article_title=$('.article_title').val();
            if(article_title==''){
                alert('标题不能为空');
                return false;
            }
            var reg=/^[a-zA-Z0-9_\u4e00-\u9fa5]+$/;
            if(!reg.test(article_title)){
                alert('标题必须是中文字母数字下划线');
                return false;
            }
            var c_name=$('#c_name').val();
            if(c_name==''){
                alert('分类不能为空');
                return false;
            }

        })
    })  
</script>