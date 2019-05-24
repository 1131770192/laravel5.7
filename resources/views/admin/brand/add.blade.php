<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="">
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
    	<form action="/brand/doadd" method="post" enctype="multipart/form-data">
            @csrf
    		<table>
    			<tr>
    				<td>品牌名称：</td>
    				<td><input type="text" name="brand_name"></td>
    			</tr>
    			<tr>
    				<td>品牌描述：</td>
                    <td><textarea name="brand_desc" cols="30" rows="10"></textarea></td>
    			</tr>
    			<tr>
    				<td>品牌LOGO</td>
    				<td><input type="file" name="brand_logo"></td>
    			</tr>
    			<tr>
    				<td>品牌网址：</td>
    				<td><input type="text" name="brand_url"></td>
    			</tr>
                <tr>
                    <td><button>提交</button></td>
                </tr>
    		</table>
            <a href="{{url('brand/lists')}}">展示</a>
    	</form>
    </body>
</html>