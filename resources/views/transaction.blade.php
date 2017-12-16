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
    <link rel="stylesheet" href="/css/cart.css">
</head>
<body>
<header class="cart-header">
    <a href="/member"><img src="/images/back.png" alt="" width="12vw"></a>交易记录
</header>

@foreach($transactions as $item)
    <section class="transactions">
        【{{ $item->type?'提现':'购物' }}】{{ $item->name }} <span>{{ $item->money }}</span>
    </section>
@endforeach

</body>
</html>