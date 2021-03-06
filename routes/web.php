<?php
Route::get('/test', 'indexController@test');
Route::get('/', 'indexController@index');
Route::get('/list/{id?}/{k?}', 'indexController@_list');
Route::any('/ajax_list/{id?}/{k?}', 'indexController@ajax_list');//ajax获取产品 下拉刷新
Route::any('/cart/deal/{id?}', 'memberController@cartDeal');
Route::any('/order/del/{id?}', 'memberController@order_del');//删除订单
Route::any('/obligation/{type?}', 'memberController@obligation');//待付款 待签收 待发货
Route::any('/order/qrsh/{id}', 'memberController@qrsh');//确认签收
Route::get('/transaction', 'memberController@transaction');
Route::any('/address/{r?}', 'memberController@address');
Route::get('/address/deal/{id?}/{t?}', 'memberController@address_deal');
Route::any('/address/edit/{id?}', 'memberController@address_edit');
Route::any('/improve', 'memberController@improve');//完善个人信息
Route::any('/getCode/{url}/{suburl}', function () {
    return view('getCode');
});//获取产品二维码

Route::any('/wechat', 'WechatController@serve');//消息回复
Route::any('/menu', 'WechatController@menu');//生成菜单
Route::group(['middleware' => 'wechat.oauth'], function () {
    Route::get('/member', 'memberController@member'); //用户页面
    Route::any('/details/{id?}/{userid?}', 'indexController@detail');//作品详情
    Route::any('/cart', 'memberController@carts');//购物车
    Route::any('/create_order', 'indexController@create_order'); //创建订单
    Route::any('/pay', 'indexController@pay');//支付
    Route::any('/packet', 'indexController@packet');//获得红包
});
Route::any('/pay/callback', 'indexController@callback');//支付回调

Route::any('/pay/success', function () {//支付成功
    return view('pay_success');
});

Route::group(['prefix' => 'server'], function () {
    Auth::routes();
    Route::get('/home', 'homeController@index')->name('home');
    Route::any('/send/money', 'homeController@sendMoney')->name('home');//通知商家已经放款
    Route::any('/apply/money', 'homeController@applyMoney')->name('home');//商家申请放款
    Route::get('/quit', 'homeController@quit')->name('quit');
    //多图片上传接口
    Route::any('/update', 'updateController@images');
    //分类管理
    Route::any('/classify', 'classifyController@_list');
    Route::any('/classify/add', 'classifyController@add');
    Route::any('/classify/del', 'classifyController@del');
    Route::any('/classify/modify', 'classifyController@modify');
    //商品管理
    Route::any('/commodity', 'commodityController@_list');
    Route::any('/commodity/add', 'commodityController@add');
    Route::any('/commodity/del', 'commodityController@del');
    Route::any('/commodity/modify', 'commodityController@modify');
    Route::any('/commodity/detail/{id}', 'commodityController@detail');
    Route::any('/commodity/soldout', 'commodityController@soldout');
    Route::any('/commodity/downCode/{id}', 'commodityController@downCode');
    //订单管理
    Route::any('/order', 'orderController@_list');
    Route::any('/order/stop', 'orderController@stop');
    Route::any('/order/send', 'orderController@send');
    Route::get('/excel/export','orderController@export');//导出exl
    //个体渠道商
    Route::any('/channel', 'channelController@_list');
    Route::any('/channel/send', 'channelController@send');
    Route::any('/channel/earnings/{id}', 'channelController@earnings');
    //注册商管理
    Route::any('/user', 'userController@_list');
    Route::any('/user/add', 'userController@add');
    Route::any('/user/del', 'userController@del');
    Route::any('/user/modify', 'userController@modify');
    //管理员管理
    Route::any('/admin', 'sundryController@admin');
    Route::any('/admin/add', 'sundryController@add');
    Route::any('/admin/del', 'sundryController@del');
    Route::any('/admin/modify', 'sundryController@modify');
    //首页轮播图管理
    Route::any('/banner', 'sundryController@banner');
    Route::any('/banner/update', 'updateController@banner');
    Route::any('/banner/del', 'sundryController@banner_del');
});