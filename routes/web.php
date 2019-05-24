<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	session(['uid'=>8]);
	// request()->session()->forget('uid');
    return view('welcome',['name'=>"开能"]);
});


//只允许get方式请求
// Route::get('/goods',function(){
//     return 123;
// });

//只允许post方式请求发送手动邮件
Route::get('/from',function(){
    return "<form action='/brand/email' method='post'>".csrf_field()."<input type=text name=email><button>提交</button></form>";
});
Route::post('/brand/email','admin\BrandController@sendemail');

//手动登录验证
Route::get('/from',function(){
    return "<form action='/brand/login' method='post'>".csrf_field()."<input type=text name=email><input type=password name=password><button>提交</button></form>";
});
Route::post('/brand/login','admin\BrandController@login');
//允许post和get请求
// Route::match(['get','post'],'/from_do',function(){
//     return request()->name;
// });
// Route::any('/from_do',function(){
//     return request()->name;
// });

//路由传参/多个参数
// Route::get('/goods/{d_id}/{id}',function($d_id,$id){
//     echo $d_id.'-'.$id;
// });

//路由传可选参数
// Route::get('/goods/{id?}',function($id=0){
//     echo $id;
//     return '开能';
// });

//路由的正则限制
Route::get('/goods/{id}',function($id){
    return $id;
})->where(['id'=>'\d+']);

//控制器响应路由
Route::get('/goods','Goods@index');


//品牌路由分组
//checklogin/auth.basic
Route::prefix('/brand')->middleware(['checklogin'])->group(function(){
	Route::get('add','admin\BrandController@create');
	Route::post('doadd','admin\BrandController@store');
	Route::get('lists','admin\BrandController@index');
	Route::get('show/{brand_id}','admin\BrandController@show');
	Route::get('del/{brand_id}','admin\BrandController@destroy');
	Route::get('edit/{brand_id}','admin\BrandController@edit');
});

//管理员路由分组
Route::prefix('/admin')->group(function(){
	Route::get('add','admin\AdminController@create');
	Route::post('doadd','admin\AdminController@store');
	Route::get('lists','admin\AdminController@index');
	Route::get('del/{admin_id}','admin\AdminController@destroy');
	Route::get('edit/{admin_id}','admin\AdminController@edit');
});

//分类路由分组
Route::prefix('/category')->group(function(){
	Route::get('add','admin\CategoryController@create');
	Route::post('doadd','admin\CategoryController@store');
	Route::get('lists','admin\CategoryController@index');
	Route::any('destroy','admin\CategoryController@destroy');
	Route::get('edit/{cate_id}','admin\CategoryController@edit');
	Route::post('update/{cate_id}','admin\CategoryController@update');
	Route::post('checkName','admin\CategoryController@checkName');
});

//文章路由分组
Route::prefix('/article')->group(function(){
	Route::get('add','admin\ArticleController@create');
	Route::post('doadd','admin\ArticleController@store');
	Route::get('lists','admin\ArticleController@index');
	Route::any('destroy','admin\ArticleController@destroy');
	Route::get('edit/{article_id}','admin\ArticleController@edit');
	Route::post('update/{article_id}','admin\ArticleController@update');
	Route::get('suibian/{article_id}','admin\ArticleController@suibian');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//首页
// Route::get('/','Index\indexController@index');
//注册路由分组
Route::prefix('/user')->group(function(){
	Route::get('add','index\UserController@create');
	Route::post('doadd','index\UserController@doadd');
	Route::post('checkName','index\UserController@checkName');
	Route::post('emailsend','index\UserController@emailsend');
	Route::post('checkYzm','index\UserController@checkYzm');
	Route::post('store','index\UserController@store');
	Route::get('login','index\UserController@login');
	Route::post('dologin','index\UserController@dologin');
	//个人信息
	Route::get('index','index\UserController@index');
	//退出登录
	Route::get('logout','index\UserController@logout');
});
//商品详情
Route::prefix('/goods')->group(function(){
	Route::get('lists/{goods_id}','index\GoodsController@lists');
	//添加购物车
	Route::post('addcart','index\GoodsController@addcart');
	//所有商品
	Route::get('prolists','index\GoodsController@prolists');
	//加入收藏
	Route::post('addCollect','index\GoodsController@addCollect');
	Route::get('collectlists','index\GoodsController@collectlists');
	//添加评论
	Route::post('addtalk','index\GoodsController@addtalk');

});
//购物车详情
Route::prefix('/cart')->group(function(){
	Route::get('lists','index\CartController@lists');
	//获取小计
	Route::post('getSubTotal','index\CartController@getSubTotal');
	//更改购买数量
	Route::post('changeBuyNumber','index\CartController@changeBuyNumber');
	//获取商品总价
	Route::post('countTotal','index\CartController@countTotal');
	//删除
	Route::post('cartDel','index\CartController@cartDel');
});
// 收货地址
Route::prefix('/address')->group(function(){
	Route::get('lists','index\AddressController@lists');
	Route::get('add','index\AddressController@add');
	// 获取区域
	Route::post('getArea','index\AddressController@getArea');
	//添加收货地址
	Route::post('addressDo','index\AddressController@addressDo');
});
// 确认结算
Route::prefix('/order')->group(function(){
	//订单表
	Route::get('lists','index\OrderController@lists');
	//提交订单
	Route::post('submitOrder','index\OrderController@submitOrder');
	//下单成功
	Route::get('successOrder','index\OrderController@successOrder');
	//立即支付电脑端
	Route::get('pcpay','index\OrderController@pcpay');
	//立即支付手机端
	Route::get('phonepay','index\OrderController@phonepay');
});
