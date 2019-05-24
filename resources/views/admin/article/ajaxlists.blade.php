
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
              <td>{{$v->article_title}}</td>
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
      