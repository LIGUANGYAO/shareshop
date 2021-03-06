<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; maximum-scale=1.0;"/>
    <meta name="author" content="李章岭"/>
    <meta name="keywords" content=""/>
    <title>{{ env('APP_NAME','')}}</title>
    <script type="text/javascript" src="/lib/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/lib/qrious.min.js"></script>
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/cart.css">
    <style>
        .pro_code {
            display: block;
            color: red !important;
            padding: 1.5vw 0;
        }
    </style>
</head>
<body>
<header class="cart-header">
    <a href="/member"><img src="/images/back.png" alt="" width="12vw"></a>获得红包
</header>
<section class="transactions" style="color: red;">
    * 点击购买产品进入详情微信分享即有机会获得现金红包<br>
    * 或者保存二维码自行分享给朋友也可有机会获得现金红包<br>
    * 分享后有人购买即可得到红包分成<br>
    * 获得奖金需要提供完整的个人信息（真实姓名和身份证号）
</section>

@foreach($transactions as $item)
    <section class="transactions">
        <a href="details/{{ $item->id }}/{{ $mid }}">
            【{{ $item->type?'提现':'购物' }}】{{ $item->name }} <span>{{ $item->money }}</span>
        </a>
        {{-- data-id="{{url('/details/'.)}}"--}}
        <a class="pro_code" href="{{url('/getCode/'.$item->id.'/'.$mid)}}">
            点击获取产品二维码
        </a>
    </section>
@endforeach

{{--<script type="text/javascript">
    $(".pro_code").click(function () {
        new QRious({
            element: $(this).next()[0],
            value: $(this).attr('data-id')
        })
    });
</script>--}}
</body>
</html>