<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; maximum-scale=1.0;"/>
    <meta name="author" content="李章岭"/>
    <meta name="keywords" content=""/>
    <title>{{ env('APP_NAME','')}}</title>
    <script type="text/javascript" src="/lib/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/lib/jquery.qrcode.min.js"></script>
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/cart.css">
</head>
<body>
<header class="cart-header">
    <a href="/member"><img src="/images/back.png" alt="" width="12vw"></a>获得红包
</header>
<section class="transactions" style="color: red;">
    * 点击购买产品进入详情微信分享即有机会获得现金红包<br><br>
    * 获得奖金需要提供完整的个人信息（真实姓名和身份证号）
</section>

@foreach($transactions as $item)
    <section class="transactions">
        <a href="details/{{ $item->id }}/{{ $mid }}">
            【{{ $item->type?'提现':'购物' }}】{{ $item->name }} <span>{{ $item->money }}</span>
        </a>
        <div id="{{ 'qrcode'.$item->id }}"></div>
        <script>
            jQuery("#{{ 'qrcode'.$item->id }}").qrcode({
                width: 100,
                height: 100,
                text: "details/{{ $item->id }}/{{ $mid }}"
            });
        </script>
    </section>
@endforeach

</body>
</html>