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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="{{url('address/add')}}" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style=background:#fff url(/index/images/xian.jpg) left center no-repeat;><a href="javascript:;" class="orange">删除信息</a></td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
      @if($addressInfo)
      @foreach($addressInfo as $v)
       <tr>
        <td width="50%">
         <h3>{{$v->address_name}}  {{$v->address_tel}}</h3>
         <time>{{$v->province}} {{$v->city}} {{$v->area}}</time>
        </td>
        <td align="right">
          <a href="address.html" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a>
          <a href="address.html" class="hui"><span class="glyphicon glyphicon-check"></span> 删除信息</a>
        </td>
       </tr>
      @endforeach
      @endif
      </table>
     </div><!--dingdanlist/-->
     
@include('public/footer')

@endsection
