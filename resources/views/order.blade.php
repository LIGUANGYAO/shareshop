<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; maximum-scale=1.0;"/>
    <meta name="author" content="李章岭"/>
    <meta name="keywords" content=""/>
    <title>EOS商城</title>
    <script type="text/javascript" src="/lib/jquery-1.10.1.min.js"></script>
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/order.css">
    <link rel="stylesheet" href="/css/iconfont.css">
</head>
<body>
<header class="address-header">
    <a href="javascript:window.history.back();"><i class="iconfont icon-fanhui"></i></a>确认订单
</header>

<section class="address">
    <p><i class="iconfont icon-shoujianren"></i>收件人:&nbsp; 王先生 &nbsp;&nbsp;&nbsp; 1365858969</p>

    <p><i class="iconfont icon-dizhi"></i>上海 上海市 环镇北路 200号 </p>
</section>

<section class="commdity">
    <div class="c_img">
        <img src="/uploads/{{ $commdity->src }}" alt="">
    </div>
    <div class="c_info">
        <h3>{{ $commdity->name }}</h3>
        <h6>单价: <span class="price">￥{{ $commdity->price }}</span> &nbsp;&nbsp;&nbsp;
            <i class="iconfont icon-cheng"></i>{{ $order->sum }}</h6>

        <p>产品属性: {{ $order->attr }}</p>
    </div>
</section>

<section class="sum">
    共 {{ $order->sum }} 件商品 &nbsp;&nbsp;&nbsp; 总计：<span class="price">￥{{ $order->money }}</span>
</section>

<input type="button" value="支付" class="pay">

<script type="text/javascript">

</script>

</body>
</html>