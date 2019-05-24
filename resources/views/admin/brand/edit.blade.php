<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    	<form action="/brand/doedit" method="post">
            @csrf
    		<table>
    			<tr>
    				<td>品牌名称：</td>
    				<td><input type="text" name="brand_name" value="{{$data->brand_name}}"></td>
    			</tr>
    			<tr>
    				<td>品牌描述：</td>
                    <td><textarea name="brand_desc" cols="30" rows="10">{{$data->brand_desc}}</textarea></td>
    			</tr>
    			<tr>
    				<td>品牌LOGO</td>
    				<td><input type="file" name="brand_logo"></td>
    			</tr>
    			<tr>
    				<td>品牌网址：</td>
    				<td><input type="text" name="brand_url" value="{{$data->brand_url}}"></td>
    			</tr>
                <input type="hidden" name="" value="{{$data->brand_id}}">
                <tr>
                    <td><button>修改</button></td>
                </tr>
    		</table>
    	</form>
    </body>
</html>